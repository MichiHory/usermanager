class Address{
    /** @type {null|int} **/
    id;

    /** @type {null|int} **/
    user_id;

    /** @type {string} **/
    street;

    /** @type {string} **/
    city;

    /** @type {int} **/
    zip;

    constructor(street, city, zip, user_id = null, id = null) {
        this.street = street;
        this.city = city;
        this.zip = zip;
        this.user_id = user_id;
        this.id = id;
    }
}