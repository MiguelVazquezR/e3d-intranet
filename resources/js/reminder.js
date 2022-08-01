var intervalId = window.setInterval(emitEvent, 60000);

function emitEvent()
{
    Livewire.emitTo('reminder.drop-down', 'showNotification');
    Livewire.emitTo('reminder.drop-down-mobile', 'showNotification');
}