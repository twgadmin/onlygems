<script src="{{{ config('settings.dropZoneJsCDN') }}}"></script>
<script type="text/javascript">

Dropzone.autoDiscover = false;

$(function() {

    Dropzone.options.avatarDropzone = {
        paramName: 'file',
        maxFilesize: 1, // MB
        addRemoveLinks: true,
        maxFiles: 1,
        acceptedFiles: ".jpeg,.jpg",
        renameFile: 'avatar.jpg',
        headers: {
            "Pragma": "no-cache"
        },
        init: function() {
            this.on("maxfilesexceeded", function(file) {
                //
            });
            this.on("maxfilesreached", function(file) {
                //
            });
            this.on("uploadprogress", function(file) {
                var html = '<div class="progress">';
                html += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%">';
                html += '</div>';
                html += '</div>';
                $('.dz-message').html(html).show();
                var userEditValue = $('.user-edit-image').val();
                console.log(userEditValue);
                if(userEditValue == true) {
                    console.log('here');
                }

            });
            this.on("maxfilesreached", function(file) {});
            this.on("complete", function(file) {
                this.removeFile(file);
            });
            this.on("success", function(file, response) {
                var html = '<div class="progress">';
                html += '<div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">';
                html += 'Upload Successful...';
                html += '</div>';
                html += '</div>';
                $('.dz-message').html(html).show();
                setTimeout(function() {
                    $('.dz-message').text('Drop files here to upload').show();
                }, 2000);


                $('#user_selected_avatar, .user-avatar-nav').attr('src', '/images/profile/{{ Auth::User()->id }}/avatar/avatar.jpg?' + new Date().getTime());
            });
            this.on("error", function(file, res) {
                var html = '<div class="progress">';
                html += '<div class="progress-bar progress-bar-striped bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">';
                html += 'Upload Failed...';
                html += '</div>';
                html += '</div>';
                $('.dz-message').html(html).show();
                setTimeout(function() {
                    $('.dz-message').text('Drop files here to upload').show();
                }, 2000);
            });
        }
    };

    if($('#avatarDropzone').length > 0) {
        var avatarDropzone = new Dropzone("#avatarDropzone");
    }










});


$(function(){
    Dropzone.autoDiscover = false;
    Dropzone.options.userAvatarDropzone = {
        paramName: 'file',
        maxFilesize: 1, // MB
        addRemoveLinks: true,
        maxFiles: 1,
        acceptedFiles: ".jpeg,.jpg",
        renameFile: 'avatar.jpg',
        headers: {
            "Pragma": "no-cache"
        },
        init: function() {
            this.on("maxfilesexceeded", function(file) {
                //
            });
            this.on("maxfilesreached", function(file) {
                //
            });
            this.on("uploadprogress", function(file) {
                var html = '<div class="progress">';
                html += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%">';
                html += '</div>';
                html += '</div>';
                $('.dz-message').html(html).show();
            });
            this.on("maxfilesreached", function(file) {});
            this.on("complete", function(file) {
                this.removeFile(file);
            });
            this.on("success", function(file, response) {
                var html = '<div class="progress">';
                html += '<div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">';
                html += 'Upload Successful...';
                html += '</div>';
                html += '</div>';
                $('.dz-message').html(html).show();
                setTimeout(function() {
                    $('.dz-message').text('Drop files here to upload').show();
                }, 2000);

                    var userid = $('.user-edit-image').val();
                    $('#edit-user-image').attr('src', '/images/profile/'+userid+'/avatar/avatar.jpg?' + new Date().getTime());

            });
            this.on("error", function(file, res) {
                var html = '<div class="progress">';
                html += '<div class="progress-bar progress-bar-striped bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">';
                html += 'Upload Failed...';
                html += '</div>';
                html += '</div>';
                $('.dz-message').html(html).show();
                setTimeout(function() {
                    $('.dz-message').text('Drop files here to upload').show();
                }, 2000);
            });
        }
    };

    if($('#userAvatarDropzone').length > 0) {
        var userAvatarDropzone = new Dropzone("#userAvatarDropzone");
    }
})
</script>
