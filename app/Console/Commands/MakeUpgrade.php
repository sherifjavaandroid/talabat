<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeUpgrade extends GeneratorCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $name = 'make:upgrade';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new upgrade interface';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type = 'Upgrade';

	/**
	 * Replace the class name for the given stub.
	 *
	 * @param  string  $stub
	 * @param  string  $name
	 * @return string
	 */
	protected function replaceClass($stub, $name = "Upgrade")
	{
		$stub = parent::replaceClass($stub, $name);
		$className = $this->argument('name');
		return str_replace('UpgradeDummy', $className, $stub);
	}

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub()
	{
		return  app_path() . '/Console/Commands/Stubs/make-upgrade.stub';
	}

	/**
	 * Get the default namespace for the class.
	 *
	 * @param  string  $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace)
	{
		return $rootNamespace . '\Upgrades';
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		$newVerisonCode = 1;
		if (\Schema::hasTable('settings')) {
			$newVerisonCode = ((int)setting('appVerisonCode', 1)) + 1;
		}

		return [
			['name', InputArgument::OPTIONAL, 'The name of the upgrade.', "Upgrade" . $newVerisonCode],
		];
	}
}
