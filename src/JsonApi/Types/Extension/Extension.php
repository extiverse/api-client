<?php

namespace Extiverse\Api\JsonApi\Types\Extension;

use Extiverse\Api\JsonApi\Types\Item;

/**
 * @property string $name
 * @property string $description
 * @property string $license
 * @property int $stars
 * @property int $forks
 * @property int $downloads
 * @property int $dependents
 * @property int $suggesters
 * @property string|null $supportEmail
 * @property string|null $supportIssues
 * @property string|null $supportForum
 * @property string|null $supportWiki
 * @property string|null $supportDocs
 * @property string|null $abandonedFor
 * @property string|null $vcs
 * @property string $createdAt
 * @property string $updatedAt
 * @property string|null $deletedAt
 * @property string|null $highestVersion
 * @property string $title
 * @property array|null $icon
 * @property bool $isPremium
 * @property int|null $teamId
 * @property bool $blacklisted
 * @property string $iconUrl
 * @property string $discussUrl
 * @property bool $compatibleWithLatestFlarum
 * @property bool|null $compatibleWith
 * @property bool $subscribed
 * @property int $subscribersCount
 * @property bool $canChangePlan
 * @property bool $canBlacklist
 * @property bool $isLocale
 * @property string $locale
 * @property string $vendor
 */
class Extension extends Item
{
    protected $type = 'extensions';
    protected ?string $flarumId = null;

    public function flarumId(): string
    {
        if (! $this->flarumId) {
            [$vendor, $package] = explode('/', $this->name);
            $package = str_replace(['flarum-ext-', 'flarum-'], '', $package);

            $this->flarumId = "$vendor-$package";
        }

        return $this->flarumId;
    }
}
