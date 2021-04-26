<?php

namespace App\Console\Commands;

use App\Contracts\OccupationParser;
use Illuminate\Console\Command;

class CrawlSkills extends Command
{
    private $parser = null;
    private $skills = [];
    private $descriptions = [];
    protected $signature = 'crawl:skills {--limit= : Limit} {--skip= : Skip N}';
    protected $description = 'Crawls skills from O*NET';

    public function __construct(OccupationParser $parser)
    {
        $this->parser = $parser;
        $this->parser->setScope('skill');
        parent::__construct();
    }

    private function print($console = true)
    {
        $line = '';
        foreach ($this->skills as $skill) {
            $line .= $skill;
            if (isset($this->descriptions[$skill])) {
                $line .= ' - ' . $this->descriptions[$skill];
            }
            $line .= PHP_EOL;
        }
        if ($console) {
            $this->line('');
            $this->comment('Skills: ' . count($this->skills));
            $this->line($line);
        }
        return $line;
    }

    private function process($skills)
    {
        foreach ($skills as $index => $skill) {
            if (!isset($this->descriptions[$skill['label']])) {
                $this->skills[] = $skill['label'];
                $this->descriptions[$skill['label']] = $skill['description'];
            }
        }
    }

    public function handle()
    {
        $limit = (int) $this->option('limit', '0');
        $skip = (int) $this->option('skip', '0');
        $occupations = $this->parser->list();
        $total = count($occupations);

        if (!$limit) {
            $limit = $total;
        }

        if ($skip) {
            $occupations = array_slice($occupations, $skip, $limit);
        }

        $this->comment('Fetching skills for ' . count($occupations) . '/' . $total . ' occupations...');
        $bar = $this->output->createProgressBar($limit);

        foreach ($occupations as $index => $occupation) {
            $occ_skills = $this->parser->get($occupation['code']);
            $this->process($occ_skills);
            $bar->advance();
            if ($index % 10 === 0) {
                $this->print();
            }
        }

        $bar->finish();
        file_put_contents('/tmp/skills.txt', $this->print(false));
        $this->info('COMPLETE');
    }
}
