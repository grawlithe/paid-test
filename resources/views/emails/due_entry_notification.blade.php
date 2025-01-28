<h1>Reminder: Your Project is Due Soon!</h1>
<p>Dear {{ $project->team->owner->name }},</p>
<p>Your {{ $project->title }} project is due in less than 24 hours.</p>
<p>Due Date: {{ $project->due_date->format('F j, Y, g:i A') }}</p>
<p>Please ensure it is completed on time.</p>
<p>Thank you!</p>
