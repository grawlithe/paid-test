<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Project;
use App\Mail\DueProjectNotification;

class NotifyDueProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:due-projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications for projects due within 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projects = Project::whereBetween('due_date', [
                now(),
                now()->addDay()
            ])
            ->where('status', '!=', 'completed')
            ->get();

        //dd($projects);

        foreach ($projects as $project) {
            // Send email notification
            Mail::to($project->team->owner->email)->send(new DueProjectNotification($project));

            $this->info("Notification sent for entry ID: {$project->id}");
            //dd($project->team->owner->email);
        }

        return Command::SUCCESS;
    }
}
