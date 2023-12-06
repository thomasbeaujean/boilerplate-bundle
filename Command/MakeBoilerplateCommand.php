<?php

namespace tbn\BoilerplateBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

#[AsCommand(
    name: 'make:boilerplate',
)]
class MakeBoilerplateCommand extends Command
{
    public function __construct(
        private Environment $twig,
        private string $projectDir,
        private array $availableTemplates,
        private array $templates,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $question = new ChoiceQuestion(
            'Please select your template',
            $this->availableTemplates,
            0,
        );

        $selectedTemplate = $io->askQuestion($question);
        $renderers = $this->templates[$selectedTemplate]['renderers'];

        $class = $io->ask('Please enter the name of the Class', 'SomeClass');
        $folder = $io->ask('Please enter the name of the Folder', 'SomePath/SomeSubPath');

        $filesystem = new Filesystem();

        foreach ($renderers as $renderer) {
            $className = ucfirst($class);
            $folders = explode('/',$folder);
            array_walk($folders, function (&$string) {
                $string =  ucfirst($string);
            });
            $folder = implode('\\', $folders);

            $folderPath = sprintf('%s/%s/%s',
                $this->projectDir,
                $renderer['folder'],
                str_replace('\\', '/', $folder),
            );
            $filesystem->mkdir($folderPath);

            $htmlContents = $this->twig->render($renderer['template'], [
                'className' => $className,
                'folder' => $folder,
            ]);

            $filename = sprintf('%s/%s%s.php', $folderPath, $className, $renderer['suffix']);
            file_put_contents($filename, $htmlContents);
        }

        $io->success('Boilerplate complete!');

        return Command::SUCCESS;
    }
}
