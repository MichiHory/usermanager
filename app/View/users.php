
<table id='users-table' class="table table-striped-rows table-responsive align-items-center">
    <thead>
    <tr class="table-primary">
        <th scope="col">Jméno</th>
        <th scope="col">Příjmení</th>
        <th scope="col">E-mail</th>
        <th scope="col">Telefon</th>
        <th scope="col">Adresa</th>
        <th scope="col" class="btns-col"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr id="user<?= $user->getId() ?>"
            data-id="<?= $user->getId() ?>"
            data-name="<?= $user->getName() ?>"
            data-surname="<?= $user->getSurname() ?>"
            data-email="<?= $user->getEmail() ?>"
            data-phone="<?= $user->getPhone() ?>"
            data-addressid="<?= $user->getAddress()?->getId() ?>"
            data-city="<?= $user->getAddress()?->getCity() ?>"
            data-street="<?= $user->getAddress()?->getStreet() ?>"
            data-zip="<?= $user->getAddress()?->getZip() ?>"
        >
            <td class="align-middle"><?= $user->getName() ?></td>
            <td class="align-middle"><?= $user->getSurname() ?></td>
            <td class="align-middle"><a href="mailto:<?= $user->getEmail()?>"><?= $user->getEmail()?></a></td>
            <td class="align-middle"><a href="tel:<?= $user->getPhone()?>"><?= $user->getPhone()?></a></td>
            <td class="align-middle"><?= $user->getAddress()?->getAddressString()?></td>
            <td class="align-middle">
                <!-- <button type="button" class="btn btn-primary btn-sm"><i class="bi bi-floppy"></i></button> -->
                <button type="button" class="btn btn-warning btn-sm edit-user" data-id="<?= $user->getId() ?>">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm delete-user" data-id="<?= $user->getId() ?>">
                    <i class="bi bi-trash"></i>
                </button>
            </td>

        </tr>
    <?php endforeach ?>

    </tbody>
</table>
<div class="d-flex justify-content-end">
    <button id="addUser" type="button" class="btn btn-primary"><i class="bi bi-person-plus"></i> Nový uživatel</button>
</div>
<div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div id="deleteUserModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Opravdu chete odstranit uživatele?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
                <button type="button" class="btn btn-danger delete-user-conf">Odstranit uživatele</button>
            </div>
        </div>
    </div>
</div>

<div id="editUserModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editace Uživatele</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userEditForm" class="row g-3 needs-validation" novalidate>
                    <input type="hidden" id="inputUserId">
                    <input type="hidden" id="inputAddressId">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="inputName" placeholder="Jméno" aria-label="Jméno" required>
                        <div class="invalid-feedback">
                            Vyplňte jméno
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="inputSurname" placeholder="Příjmení" aria-label="Příjmení" required>
                        <div class="invalid-feedback">
                            Vyplňte Příjmení
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Email</label>
                        <input type="email" class="form-control" id="inputEmail" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Heslo</label>
                        <input type="password" class="form-control" id="inputPassword" required>
                        <div class="invalid-feedback">
                            Vyplňte Heslo
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="inputPhone" class="form-label">Telefon</label>
                        <input type="text" class="form-control" id="inputPhone">
                    </div>
                    <div class="col-12">
                        <label for="inputAddress" class="form-label">Adresa</label>
                        <input type="text" class="form-control" id="inputStreet" placeholder="Poděbradská 14">
                        <div class="invalid-feedback">
                            Vyplňte všechny položky adresy nebo žádnou
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label for="inputCity" class="form-label">Město</label>
                        <input type="text" class="form-control" id="inputCity">
                        <div class="invalid-feedback">
                            Vyplňte všechny položky adresy nebo žádnou
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="inputZip" class="form-label">PSČ</label>
                        <input type="text" class="form-control" id="inputZip">
                        <div class="invalid-feedback">
                            Vyplňte všechny položky adresy nebo žádnou
                        </div>
                    </div>
                    <div id="errorMessage" class="col-md-12" style="color: darkred">

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
                <button type="submit" class="btn btn-primary edit-user-conf" form="userEditForm">Uložit</button>
            </div>
        </div>
    </div>
</div>