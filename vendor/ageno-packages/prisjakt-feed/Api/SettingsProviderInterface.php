<?php

namespace Ageno\Prisjakt\Api;

interface SettingsProviderInterface {

	public function getBatchSize(): int;

	public function setBatchSize( int $batchSize): void;
}
