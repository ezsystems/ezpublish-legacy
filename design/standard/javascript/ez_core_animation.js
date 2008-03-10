/*
    eZ Core Animation: animation extension for ez core js library
    Created on: <09-Des-2007 00:00:00 ar>
    
    Copyright (c) 2007-2008 eZ Systems AS
    Licensed under the MIT License:
    http://www.opensource.org/licenses/mit-license.php
    
*/

if ( window.ez !== undefined && window.ez.animation === undefined )
{

ez.animation = {
    transition: {
        // Included set of transition effects's to use with animations
        cubic:    function( p ){ p = Math.pow( p, 3 ); return { def: p }},
        circ:     function( p ){ p = Math.sqrt( p ); return { def: p }},
        sinoidal: function( p ){ p = ( (-Math.cos( p * Math.PI ) / 2) + 0.5 ); return { def: p }}
    },
    styles: {
        /* Default Animation Style Objects
           Style Object peroperties:
           unit [optional]: unit type for style, % will force the animation to threat number as % an
               caclulate it as such. '' will skipp calculation anything, threating it as a string style.
           handler [optional]: custom style handler for styles that need special care / calculation.
           cleanup [optional]: custom style handler that are run after main animation is done.
        */
        width: {unit: 'px'},
        height: {unit: 'px'},
        top: {unit: 'px'},
        left: {unit: 'px'},
        marginLeft: {unit: 'px'},
        marginTop: {unit: 'px'},
        fontSize: {unit: '%'},
        opacity: {unit: '0', handler: function( steps, direction, y )
        {
            if ( this.el.style.opacity !== undefined ) this.el.style.opacity = (y >= 1) ? '' : y;
            else if ( this.el.style.filter !== undefined ) this.el.style.filter = (y >= 1) ? '' : 'alpha(opacity=' + y*100 + ')';
        }},
        display: {handler: function( steps, direction, y, style )
        {
            if( this.step === 0 && !direction ) this.el.style[style] = this.settings.origin[style];
            else if ( this.step === steps + 1 && direction) this.el.style[style] = this.settings.target[style];
        }}
    }
};

// Add cleanup handler on display style
ez.animation.styles.display.cleanup = ez.animation.styles.display.handler;

// Extending animation styles to support scrolling
ez.animation.styles.scrollTop = ez.animation.styles.scrollLeft = {
    unit: 'px',
    handler: function( steps, direction, y, style ){ this.el[style] = y; }
};

// Extending animation styles with visibility, and make it behave like display
ez.animation.styles.visibility = ez.animation.styles.display;

// shortcut / to avoid bc for transition's
ez.fx = ez.animation.transition;

ez.object.extend(ez.element.eZextensions.prototype, {
    animate:  function( direction, settings, origin, target, onComplete, clear )
    {
        // Animates the element, all parameters are optional
        // Example: ez.$('my_el').animate( true, {duration: 400}, {height:300}, function(){ alert('done')} );
        /* parameter:
           direction (boolean): specifies the direction you want to go, if target is false it
                             means you want to animate the element back to it's initial style
           settings (object): Is threated as duration if number, see setSettings for more info
           origin (object): See animationTarget for more info
           target (object): --"--
           onComplete (function): see animationStep for more info
           clear (boolean): see animationTarget for more info
        */
        if ( this.direction !== direction || !this.timeout )
        {
            this.animationStop();
            settings = this.setSettings( ez.num( settings ) ? { duration: settings } : settings );
            // calculate time pr frame and number of steps in animation based on fps (def: 35)
            this.settings.frametime = Math.round( 1000 / settings.fps );
            var steps = Math.round( settings.duration / this.settings.frametime );
            this.step = this.direction === null ? 0 : ez.max( steps - this.step, 0 );
            this.direction = direction;
            if ( target ) this.animationTarget( target, origin, clear );
            this.animationStep( steps, direction, onComplete || false );
        }
        return this;
    },
    animationStep: function( steps, direction, onComplete )
    {
        // This is the animation 'engine' and it's is called from .animate()
        // For customcallback pass a function in onComplete when you call animate
        if ( direction !== this.direction ) return;
        var s = this.settings, els = this.el.style, t = s.target, o = s.origin;
        if (this.step === steps + 1)
        {
            if ( direction && s.onTarget ) s.onTarget(this, this.el);
            for ( style in t )
            {
                if ( ez.animation.styles[style] && ez.animation.styles[style].cleanup )
                    ez.animation.styles[style].cleanup.call( this, steps, direction, 0, style );
            }
            if ( onComplete ) onComplete(this, this.el, direction);
        }
        else
        {
            var pos = s.transition(( direction ? 1 - this.step / steps : this.step / steps ));
            for ( style in t )
            {
                if ( !ez.animation.styles[style] ) continue;
                var styleObject = ez.animation.styles[style], y = pos[style] || pos['def'];
                if ( styleObject.unit )
                {
                    var rel = styleObject.unit === '%', x = rel ? 1 : o[style];
                    y = ((t[style] - x) * (1 - y) + x) * ( rel ? 100 : 1 );
                }
                if ( styleObject.handler ) styleObject.handler.call( this, steps, direction, y, style );
                else els[style] = y + styleObject.unit || '';
            }
            if ( this.step === 0 && !direction && s.onOrigin ) s.onOrigin(this, this.el);
            ++this.step;
            this.timeout = setTimeout( ez.fn.bind( this.animationStep, this, steps, direction, onComplete ),  s.frametime);
        }
    },
    animationStop: function()
    {
        // Stops any current animation or does nothing if no active animations exists
        clearTimeout(this.timeout);
        this.timeout = null;
        return this;
    },
    animationTarget: function( target, origin, clear )
    {
        // Function to prepare the target object and calculating the elements current style
        // you can override origin style with the origin object
        // And it's also possible to recalculate the current style by setting clear = true
        // Example  el.animationTarget({height:400,opacity:0.2});
        var o = ez.object.extend( this.settings.origin, origin || {}, true ), a = ez.animation.styles, v, t = target;
        if (clear) this.settings.target = {};
        if ( ez.string.is( target ) )
        {
            // If target is string, threat it as shortcut for {'target': 0}
            t = {};
            t[target] = 0;
        }
        for (style in t)
        {
            if ( !ez.set(a[style]) ) continue;
            // Only calcualte current style if it isn't already or if clear is true
            if ( o[style] === undefined || clear )
            {
                v = this.getStyle( style );
                o[style] = !a[style].unit ? v : ez.num( v, 0);
            }
            this.settings.target[style] = t[style];
        }
        return this;
    },
    direction: null,
    hide: function( settings, target, onComplete )
    {
        // Wrapper function for toggle, will animate in the target direction
        return this.toggle( settings, target, onComplete, true );
    },
    show: function( settings, target, onComplete )
    {
        // Wrapper function for toggle, will animate back to the initial style (element origin)
        return this.toggle( settings, target, onComplete, false );
    },
    timeout: null,
    toggle: function( settings, target, onComplete, direction )
    {
        // Wrapper function for animate, toggle visibility with animation
        // target is the hidden state, origin state will be calculated from current state
        // except display will be forced to visiable style in case element is pre hidden
        // see animate for description on parameters
        if ( !target ) target = {display:'none'};
        if ( direction === undefined ) direction = (this.direction === null ? (this.getStyle('display') !== 'none' && this.getStyle('visibility') !== 'hidden') : (this.timeout === null ? this.direction : !this.direction ));
        return this.animate( direction, settings, { display:'', visibility: 'visible' }, target, onComplete );
    }
}, true);

}//if ( window.ez !== undefined && window.ez.animation === undefined )