$.extend($.validator.messages, {
    required: "Field ini harus diisi.",
    remote: "Mohon perbaiki field ini.",
    email: "Mohon masukan alamat email dengan benar.",
    url: "Mohon masukan URL dengan benar.",
    date: "Mohon masukan tanggal dengan benar.",
    dateISO: "Mohon masukan tanggal sesuai (ISO).",
    number: "Mohon masukan angka.",
    digits: "Mohon masukan hanya angka.",
    creditcard: "Mohon masukan nomor credit card dengan benar.",
    equalTo: "Mohon masukan isi yang sama.",
    accept: "Mohon masukan nilai dengan ekstensi yang benar.",
    maxlength: jQuery.validator.format("Mohon tidak memasukan lebih dari {0} karakter."),
    minlength: jQuery.validator.format("Mohon masukan minimal {0} karakter."),
    rangelength: jQuery.validator.format("Mohon masukan panjang karakter antara {0} dan {1} karakter."),
    range: jQuery.validator.format("Mohon masukan nilai antara {0} dan {1}."),
    max: jQuery.validator.format("Mohon masukan nilai kurang dari atau sama dengan {0}."),
    min: jQuery.validator.format("Mohon masukan nilai lebih dari atau sama dengan {0}.")
});