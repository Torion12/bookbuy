<script src="/node_modules/jquery/dist/jquery.min.js"></script>
<script src="/node_modules/toastr/build/toastr.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/print.min.js"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>

<?php if(isset($user) && $user->isLoggedIn()) { ?>
// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('9793b4a9d2a3567cf558', {
  cluster: 'ap1'
});

var channel = pusher.subscribe('<?php echo $user->role() ?>');
channel.bind('notification', function(data) {
  alert(JSON.stringify(data));
});

<?php } ?>
</script>