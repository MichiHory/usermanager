$(document).ready(function() {
    let $userEditForm = $('#userEditForm');

    /**
     * Displays a modal window for user editing
     */
    $('#addUser').on('click', (e) => {
        showUserEditModal();
    });

    /**
     * Displays a modal window for user editing
     */
    $(document).on('click', '.edit-user', (e) => {
        let userId = $(e.currentTarget).data('id');
        showUserEditModal(userId);
    });

    /**
     * Displays a dialog for deleting a user
     */
    $(document).on('click', '.delete-user',  (e) => {
        let userId = $(e.currentTarget).data('id');
        let $deleteUserModal = $('#deleteUserModal');

        $deleteUserModal.data('id', userId)
        $deleteUserModal.modal('show');
    });

    /**
     * Deletes the user
     */
    $('.delete-user-conf').on('click', (e) => {
        let user = new User();
        let userId = $('#deleteUserModal').data('id');

        user.deleteUser(userId).then((e) => {
            if(e.success){
                $('#deleteUserModal').modal('hide');
                $('#user' + userId).hide("slow", function(){ $(this).remove(); })
            }
        });
    });


    $userEditForm.on('submit', (e) => {
        let form = e.target;
        e.preventDefault();
        $('#errorMessage').text('');
        checkAddressFields();
        if(form.checkValidity()){
            e.stopPropagation();
            let user = createUser();

            user.saveUser().then((e) => {
                if(e.success){
                    $('#editUserModal').modal('hide');
                    if(typeof e.data.userId != 'undefined' && user.id === ''){
                        user.id = e.data.userId;
                        addUserLine(user);
                    }else if(user.id !== ''){
                        location.reload()
                    }
                }else{
                    $('#errorMessage').text(e.errorMsg);
                }
            });
        }

        form.classList.add('was-validated')
        return false;
    });

    /**
     * Displays a modal window for user editing
     * @param userId
     */
    function showUserEditModal(userId = null) {
        $userEditForm[0].classList.remove('was-validated');
        let $editUserModal = $('#editUserModal');

        let user = new User();

        if(Number.isInteger(userId)){
            loadDataToModal(userId);
        }else{
            clearUserEditModal();
        }

        $editUserModal.modal('show');
    }

    /**
     * Fills the user edit form with the selected user's data
     * @param userId
     */
    function loadDataToModal(userId = null){
        if(userId === null) return;

        clearUserEditModal();
        let $userSource = $('tr#user' + userId)
        $('#inputPassword').prop('required',false);

        $('#inputName').val($userSource.data('name'))
        $('#inputSurname').val($userSource.data('surname'))
        $('#inputEmail').val($userSource.data('email'))
        $('#inputPhone').val($userSource.data('phone'))
        $('#inputStreet').val($userSource.data('street'))
        $('#inputCity').val($userSource.data('city'))
        $('#inputZip').val($userSource.data('zip'))
        $('#inputUserId').val($userSource.data('id'))
        $('#inputAddressId').val($userSource.data('addressid'))
    }

    /**
     * Clears all data from the user edit form
     */
    function clearUserEditModal(){
        $('#inputName').val('')
        $('#inputSurname').val('')
        $('#inputEmail').val('')
        $('#inputPhone').val('')
        $('#inputPassword').val('')
        $('#inputStreet').val('')
        $('#inputCity').val('')
        $('#inputZip').val('')
        $('#inputUserId').val('')
        $('#inputAddressId').val('')

        $('#inputPassword').prop('required',true);
        $('#inputStreet').prop('required',false);
        $('#inputCity').prop('required',false);
        $('#inputZip').prop('required',false);
    }

    /**
     * If at least one address item is filled in, all of them must be filled in.
     */
    function checkAddressFields(){
        let street = $('#inputStreet').val()
        let city = $('#inputCity').val()
        let zip = $('#inputZip').val()

        if(street != '' || street != '' || zip != ''){
            $('#inputStreet').prop('required',true);
            $('#inputCity').prop('required',true);
            $('#inputZip').prop('required',true);
        }else{
            $('#inputStreet').prop('required',false);
            $('#inputCity').prop('required',false);
            $('#inputZip').prop('required',false);
        }
    }

    /**
     * Creates a user object according to data from the form
     * @returns {User}
     */
    function createUser(){
        let user = new User();
        user.id = $('#inputUserId').val()
        user.name = $('#inputName').val()
        user.surname = $('#inputSurname').val()
        user.email = $('#inputEmail').val()
        user.phone = $('#inputPhone').val()
        user.password = $('#inputPassword').val()

        let address = new Address()
        address.id = $('#inputAddressId').val();
        address.user_id = $('#inputUserId').val();
        address.street = $('#inputStreet').val();
        address.city = $('#inputCity').val();
        address.zip = $('#inputZip').val();

        user.address = address;

        return user;
    }

    /**
     * Adds a row to the user table
     * @param user{User}
     */
    function addUserLine(user){
        let line = `
        <tr id="user${user.id}"
            data-id="${user.id}"
            data-name="${user.name}"
            data-surname="${user.surname}"
            data-email="${user.email}"
            data-phone="${user.phone}"
            data-addressid=""
            data-city="${user.address.city}"
            data-street="${user.address.street}"
            data-zip="${user.address.zip}"
        >
            <td class="align-middle">${user.name}</td>
            <td class="align-middle">${user.surname}</td>
            <td class="align-middle"><a href="mailto:${user.email}">${user.email}</a></td>
            <td class="align-middle"><a href="tel:${user.phone}">${user.phone}</a></td>
            <td class="align-middle">${user.address.street} <br> ${user.address.zip} ${user.address.city}</td>
            <td class="align-middle">
                <!-- <button type="button" class="btn btn-primary btn-sm"><i class="bi bi-floppy"></i></button> -->
                <button type="button" class="btn btn-warning btn-sm edit-user" data-id="${user.id}">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm delete-user" data-id="${user.id}">
                    <i class="bi bi-trash"></i>
                </button>
            </td>

        </tr>
        `;

        $('#users-table tbody').append(line);
    }
});

