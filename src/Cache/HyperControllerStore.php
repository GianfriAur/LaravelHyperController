<?php

namespace Gianfriaur\HyperController\Cache;

use Exception;
use Illuminate\Cache\ArrayStore;
use Illuminate\Filesystem\Filesystem;

class HyperControllerStore extends ArrayStore
{

    private readonly Filesystem $files;
    private readonly string $cacheFilePath;

    protected bool $loaded = false;

    public function __construct(Filesystem $files, string $cacheFilePath, bool $serializesValues = false)
    {
        parent::__construct($serializesValues);
        $this->files = $files;
        $this->cacheFilePath = $cacheFilePath;
    }

    protected function load()
    {
        $this->storage =
            is_file($this->cacheFilePath)
                ? $this->files->getRequire($this->cacheFilePath)
                : [];

        $this->loaded = true;
    }

    protected function update()
    {

        if (! is_writable($dirname = dirname($this->cacheFilePath))) {
            throw new Exception("The {$dirname} directory must be present and writable.");
        }

        $this->files->replace(
            $this->cacheFilePath, '<?php return '.var_export( $this->storage, true).';'
        );

    }

    public function get($key)
    {
        if (!$this->loaded) {
            $this->load();
        }

        return parent::get($key);
    }


    public function put($key, $value, $seconds): bool
    {
        $value = parent::put($key, $value, $seconds);

        $this->update();

        return $value;
    }

    public function increment($key, $value = 1)
    {
        $value = parent::increment($key, $value);

        $this->update();

        return $value;
    }

    public function forget($key)
    {
        $value = parent::forget($key);

        $this->update();

        return $value;
    }

    public function flush()
    {
        $value = parent::flush();

        $this->update();

        return $value;
    }

    public function getKeys()
    {
        if (!$this->loaded) {
            $this->load();
        }

        return array_keys($this->storage);
    }

}