<form method="post" action={"/notification/runfilter/"|ezurl}>

<h1>Notification admin</h1>

{section show=$filter_proccessed}
<div class="feedback">
Notification filter proccessed all available notification events
</div>
{/section}

{section show=$time_event_created}
<div class="feedback">
Time event was spawned
</div>
{/section}

<h2>Run notification filter</h2>

<input type="submit" name="RunFilterButton" value="Run" />

<h2>Spawn time event</h2>

<input type="submit" name="SpawnTimeEventButton" value="Spawn" />
</form>