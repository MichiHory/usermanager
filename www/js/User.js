class User{
    /** @type {?int} **/
    id;

    /** @type {string} **/
    password;

    /** @type {string} **/
    name;

    /** @type {string} **/
    surname;

    /** @type {string} **/
    email;

    /** @type {?string} **/
    phone;

    /** @type {?Address} **/
    address;

    /**
     *
     * @param email : string
     * @param password : string
     * @param name : string
     * @param surname : string
     * @param phone : ?string
     * @param address : ?Address
     * @param id : ?int
     */
    createUser(email, password, name, surname, phone = null, address = null, id = null){
        this.email      = email;
        this.password   = password;
        this.name       = name;
        this.surname    = surname;
        this.phone      = phone;
        this.id         = id;
        this.address    = address;
    }

    deleteUser(id){
        return $.ajax({
            type: "POST",
            url: '/deleteUser',
            data: {userId: id},
            dataType: 'json',
        });
    }

    saveUser(){
        return $.ajax({
            type: "POST",
            url: '/saveUser',
            data: {user: this},
            dataType: 'json',
        });
    }
}