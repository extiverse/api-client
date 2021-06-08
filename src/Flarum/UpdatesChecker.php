<?php

namespace Extiverse\Api\Flarum;

use Composer\Semver\Comparator;
use Extiverse\Api\JsonApi\Repositories\ExtensionRepository;
use Extiverse\Api\JsonApi\Types\Extension\Extension;
use Flarum\Extension\ExtensionManager;
use Flarum\Foundation\Application;
use Illuminate\Support\Collection;

class UpdatesChecker
{
    private ExtensionManager $extensions;

    public function __construct(ExtensionManager $extensions)
    {
        $this->extensions = $extensions;
    }

    public function core(): Extension
    {
        $response = $this->repository()->find('flarum$core');

        /** @var Extension $core */
        $core = $response->getData();
        $core->setAttribute('current-version', Application::VERSION);
        $core->setAttribute('enabled', true);
        $core->setAttribute('needs-update', $this->needsUpdate($core['current-version'], $core['highest-version']));

        return $core;
    }

    public function process(): Collection
    {
        return $this->extensions->getExtensions()
            ->pluck('name')
            ->map(function (string $name) {
                return str_replace('/', '$', $name);
            })
            ->chunk(15)
            ->map(function ($chunk) {
                $response = $this->repository()->all(['filter[batch]' => $chunk->implode(',')]);

                return $response->getData();
            })
            ->collapse()
            ->sortBy('attributes.name')
            ->map(function (Extension $extension) {
                $installed = $this->extensions->getExtension($extension->flarumId());

                $extension->setAttribute('current-version', $installed->getVersion());
                $extension->setAttribute('enabled', $this->extensions->isEnabled($extension->flarumId()));
                $extension->setAttribute('needs-update', $this->needsUpdate($extension['current-version'], $extension['highest-version']));

                return $extension;
            })
            ->values();
    }

    protected function needsUpdate(string $currentVersion, string $highestVersion = null): bool
    {
        if (!$highestVersion || substr($currentVersion, 0, 4) === 'dev-')  return false;

        return Comparator::compare($currentVersion, '<', $highestVersion);
    }

    protected function repository(): ExtensionRepository
    {
        return new ExtensionRepository;
    }
}
