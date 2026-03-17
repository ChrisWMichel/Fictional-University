import $ from "jquery"

class myNotes {

    constructor() {
        
        this.events();
    }

    events() {
        $('.update-note').on('click', function() {
            const noteId = $(this).closest('li').data('id');
            const thisNote = $(this).closest('li');            
            $.ajax({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
                },
                url: universityData.root_url + '/wp-json/wp/v2/note/' + noteId,
                type: 'POST',
                data: {
                    title: thisNote.find('.note-title').val(),
                    content: thisNote.find('.note-body').val()
                },
                success: (response) => {
                    thisNote.find('.edit-note').html('<i class="fa fa-pencil" aria-hidden="true"></i>');
                    thisNote.find('.note-title').attr('readonly', 'readonly');
                    thisNote.find('.note-body').attr('readonly', 'readonly');
                    thisNote.find('.update-note').removeClass('update-note--visible');
                    thisNote.find('.note-title').removeClass('note-active-field');
                    //console.log(response);
                },
                error: (response) => {
                    console.log('recordIDError: ' + noteId);
                    //console.log(response);
                }
            });
        });

        $('.edit-note').on('click', function() {
            const $thisNote = $(this).closest('li');
            
            var $noteTitle = $thisNote.find('.note-title').addClass('note-active-field');
            var $noteBody = $thisNote.find('.note-body');
            const $updateBtn = $thisNote.find('.update-note').addClass('update-note--visible');
            
            if($noteTitle.is('[readonly]')) {
                $thisNote.find('.edit-note').html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');
                $noteTitle.removeAttr('readonly').trigger('focus');
                $noteBody.removeAttr('readonly');
            } else {
                $thisNote.find('.edit-note').html('<i class="fa fa-pencil" aria-hidden="true"></i>');                
                $noteTitle.attr('readonly', 'readonly');
                $noteBody.attr('readonly', 'readonly');
                $updateBtn.removeClass('update-note--visible');
                $noteTitle.removeClass('note-active-field');

            }
        });

        $('.delete-note').on('click', function() {
            const noteId = $(this).closest('li').data('id');
            const thisNote = $(this).closest('li');            
            $.ajax({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
                },
                url: universityData.root_url + '/wp-json/wp/v2/note/' + noteId,
                type: 'DELETE',
                success: (response) => {
                    thisNote.slideUp();
                    if(response.userNoteCount < 5) {
                        $('.note-limit-message').removeClass('active');
                    }
                },
                error: (response) => {
                    console.log('recordIDError: ' + noteId);
                    //console.log(response);
                }
            });

        });

        $('.submit-note').on('click', function() {
            const $newNoteTitle = $('#new-note-title');
            const $newNoteBody = $('#new-note-body');

            $.ajax({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
                },
                url: universityData.root_url + '/wp-json/wp/v2/note',
                type: 'POST',
                data: {
                    title: $newNoteTitle.val(),
                    content: $newNoteBody.val(),
                    status: 'publish'
                },
                success: (response) => {
                    $newNoteTitle.val('');
                    $newNoteBody.val('');
                    // Optionally, you can prepend the new note to the list of notes
                    $('#my-notes').prepend(`
                        <li data-id="${response.id}">
                            <input class="note-title note-title-field" value="${response.title.rendered}" readonly>
                            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                            <span class="delete-note"><i class="fa fa-trash" aria-hidden="true"></i></span>
                            
                            <textarea class="note-body note-body-field" readonly>${$('<div>').html(response.content.rendered).text()}</textarea>
                         
                            <span class="update-note btn btn--blue btn--small"><i style="padding-right: 5px;" class="fa fa-arrow-right" aria-hidden="true"></i>Update</span>
                        </li>
                    `);
                    $('.create-note').slideUp();
                    $('.create-note-link').removeClass('hidden');

                },
                error: (response) => {
                    if(response.responseText === 'You have reached your note limit.') {
                        $('.note-limit-message').addClass('active');
                    } else {
                        console.log('Error creating note');
                        console.log(response);
                    }
                }
            });
        });

        $('.create-note-link').on('click', function() {
            $('.create-note').slideToggle();
            $('.create-note-link').addClass('hidden');
        });

        $('.cancel-note').on('click', function() {
            $('.create-note').slideUp();
            $('.create-note-link').removeClass('hidden');
        });

    }
}

export default myNotes;