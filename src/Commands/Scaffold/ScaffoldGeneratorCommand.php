<?php

namespace InfyOm\Generator\Commands\Scaffold;

use InfyOm\Generator\Commands\BaseCommand;
use InfyOm\Generator\Utils\FileUtil;

class ScaffoldGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'infyom:scaffold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full CRUD views for given model';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->checkIsThereAnyDataToGenerate()) {
            $this->fireEvent('scaffold', FileUtil::FILE_CREATING);
            $this->generateCommonItems();

            $this->generateScaffoldItems();

            $this->performPostActionsWithMigration();
            $this->fireEvent('scaffold', FileUtil::FILE_CREATED);
        } else {
            $this->config->commandInfo('There are not enough input fields for scaffold generation.');
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), []);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array_merge(parent::getArguments(), []);
    }

    /**
     * Check if there is anything to generate.
     *
     * @return bool
     */
    protected function checkIsThereAnyDataToGenerate()
    {
        if (count($this->config->fields) > 1) {
            return true;
        }
    }
}
