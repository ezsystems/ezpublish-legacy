# Configurable DFS cluster backend

The DFS cluster uses two handlers: DB, and FS. So far, the FS handler was hardcoded and couldn't be changed. Starting
from 5.4, it can be set in an INI file.

## Configuration

A new INI setting, `eZDFSClusteringSettings.DFSBackend`, is introduced. It expects the name of a class that implements
`eZDFSFileHandlerDFSBackendInterface`:

```ini
[eZDFSClusteringSettings]
DFSBackend=MyDFSBackend
```

## Backend class initialization
By default, the configured backend will be created with the `new` keyword, without any argument.

If complex initialization steps are required, backends can implement `eZDFSFileHandlerDFSBackendFactoryInterface`. This
interface has a `build()` static method. Within the implementation, dependencies can be resolved, and configuration
loaded.

## Limitations
Due to the order of settings parsing, cluster related settings must be placed in the global `file.ini` override, and not
in an extension or a siteaccess.
