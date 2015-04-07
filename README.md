# kasha-caching

This library is an extremely simple filesystem-based Cache.

While it can be used for simple storage, most probably you will want to extend this functionality. You are welcome to do so.

## Installation

Install Temple with Composer by adding a requirement into composer.json of your project:

```json
{
   "require": {
        "barcodex/kasha-caching": "*"
   }
}
```

or requiring it from the command line:

```bash
composer require barcodex/kasha-caching:*
```

## API

Cache class is the only one in the library, and it has fairly few methods with self-explainable names.

Use *get*/*set* methods to cache a value or retrieve the one from the cache (false is returned if nothing is found).

Whenever you want to be sure beforehand whether key exists, use *hasKey* methods.

If you want to delete a key, use *delete* method.

Cache class is a singleton - you always work with the same instance of it while the script runs.
To retrieve the instance, use *getInstance* method.

Cache is filesystem based, so it should know the folder where it is initialized into.
Use *setRootFolder* when you have got the instance of the object for the first time.
At any later point, running *getRootFolder* on the instance variable would return the path.

API does not care about the hierarchy of the keys you want to have, but the Cache class is ready for that.
Whenever you specify the key as a string containing slashes, Cache will automatically create the folder structure.

Later you can do some bulk operations with the keys that have the same prefix.
So far, there are only two methods - *listKeysByprefix* and *deleteByPrefix*

