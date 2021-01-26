const initActionButton = (table) => {
    table.on('select', function (e, dt, type, indexes) {
        let length = table.rows('.selected').data().length;
        if (length === 1) {
            table.buttons(['.actions']).enable();
        } else {
            table.buttons(['.actions']).disable();
        }
    });

    table.on('deselect', function (e, dt, type, indexes) {
        table.buttons(['.actions']).disable();
    });
}
