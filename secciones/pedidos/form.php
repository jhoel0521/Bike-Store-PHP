<?php
// Variables esperadas: $pedido (null|array), $items (array), $clientes, $productos
$pedido = $pedido ?? null;
$items = $items ?? [];
$clientes = $clientes ?? [];
$productos = $productos ?? [];
?>
<form method="post" action="<?= htmlspecialchars($form_action ?? 'guardar.php') ?>" id="form-pedido">
    <?php if ($pedido) { ?>
        <input type="hidden" name="txtID" value="<?= htmlspecialchars($pedido['order_id']) ?>" />
    <?php } ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Cliente</label>
            <select name="customer_id" class="form-select" required>
                <option value="">Seleccione...</option>
                <?php foreach ($clientes as $c) {
                    $sel = ($pedido && $pedido['customer_id'] == $c['customer_id']) ? 'selected' : ''; ?>
                    <option value="<?= $c['customer_id'] ?>" <?= $sel ?>><?= htmlspecialchars($c['customer_name'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Fecha</label>
            <input type="datetime-local" name="order_date" class="form-control" value="<?= $pedido ? date('Y-m-d\TH:i', strtotime($pedido['order_date'])) : date('Y-m-d\TH:i') ?>" required />
        </div>
    </div>

    <hr class="my-4" />

    <h5>Ítems</h5>
    <div class="table-responsive mb-3">
        <table class="table table-sm align-middle" id="items-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="width:100px">Cantidad</th>
                    <th style="width:140px">Precio</th>
                    <th style="width:120px">Descuento %</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)) {
                    foreach ($items as $it) { ?>
                        <tr data-item-id="<?= $it['order_items_id'] ?>">
                            <td><?= htmlspecialchars($it['product_name'] ?? 'Producto', ENT_QUOTES, 'UTF-8') ?>
                                <input type="hidden" name="product_id[]" value="<?= $it['product_id'] ?>">
                            </td>
                            <td><input type="number" name="quantity[]" class="form-control form-control-sm" value="<?= $it['quantity'] ?>" min="1" required></td>
                            <td><input type="number" step="0.01" name="price[]" class="form-control form-control-sm" value="<?= $it['price'] ?>" required></td>
                            <td><input type="number" step="0.01" name="discount[]" class="form-control form-control-sm" value="<?= $it['discount'] ?>"></td>
                            <td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteItem(<?= $it['order_items_id'] ?>, this)"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td>
                            <select class="form-select form-select-sm product-select">
                                <option value="">Seleccione...</option>
                                <?php foreach ($productos as $p) { ?>
                                    <option value="<?= $p['product_id'] ?>" data-price="<?= $p['price'] ?>"><?= htmlspecialchars($p['product_name'], ENT_QUOTES, 'UTF-8') ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="product_id[]" class="product-id">
                        </td>
                        <td><input type="number" name="quantity[]" class="form-control form-control-sm" value="1" min="1" required></td>
                        <td><input type="number" step="0.01" name="price[]" class="form-control form-control-sm price-input" value="0.00" required></td>
                        <td><input type="number" step="0.01" name="discount[]" class="form-control form-control-sm" value="0"></td>
                        <td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex gap-2 mb-3">
        <button type="button" id="add-row" class="btn btn-sm btn-outline-primary">Añadir ítem</button>
        <?php if (!$pedido || (isset($form_action) && $form_action === 'editar.php')) { ?>
            <button type="submit" class="btn btn-primary"><?= $pedido ? 'Guardar cambios' : 'Guardar pedido' ?></button>
        <?php } else { ?>
            <span class="badge bg-info-subtle text-info align-self-center">Visualizando pedido existente. Añade ítems individualmente.</span>
        <?php } ?>
    </div>

</form>

<script>
    (function() {
        const productos = <?php echo json_encode($productos, JSON_UNESCAPED_UNICODE); ?>;
        window.existingOrderId = <?= $pedido ? (int)$pedido['order_id'] : 'null' ?>;

        function createRow(isForExistingOrder = false) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select class="form-select form-select-sm product-select">
                        <option value="">Seleccione...</option>
                        ${productos.map(p=>`<option value="${p.product_id}" data-price="${p.price}">${p.product_name}</option>`).join('')}
                    </select>
                    <input type="hidden" name="product_id[]" class="product-id">
                </td>
                <td><input type="number" name="quantity[]" class="form-control form-control-sm" value="1" min="1" required></td>
                <td><input type="number" step="0.01" name="price[]" class="form-control form-control-sm price-input" value="0.00" required></td>
                <td><input type="number" step="0.01" name="discount[]" class="form-control form-control-sm" value="0"></td>
                <td class="text-end">${isForExistingOrder ? '<button type="button" class="btn btn-sm btn-primary add-existing">Agregar</button>' : '<button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>'}</td>
            `;
            attachRowEvents(tr);
            return tr;
        }

        function attachRowEvents(tr) {
            const select = tr.querySelector('.product-select');
            const hid = tr.querySelector('.product-id');
            const priceInput = tr.querySelector('.price-input');
            if (select) {
                select.addEventListener('change', function() {
                    const opt = this.selectedOptions[0];
                    hid.value = this.value;
                    if (opt) priceInput.value = opt.dataset.price ?? 0;
                });
            }
            const remove = tr.querySelector('.remove-row');
            if (remove) remove.addEventListener('click', () => tr.remove());
        }

        document.getElementById('add-row').addEventListener('click', function() {
            const tbody = document.querySelector('#items-table tbody');
            const row = createRow(!!existingOrderId);
            tbody.appendChild(row);
        });

        document.querySelectorAll('#items-table tbody tr').forEach(attachRowEvents);
    })();

    function deleteItem(itemId, btn) {
        if (!confirm('Eliminar línea?')) return;
        fetch('items.php?txtID=' + encodeURIComponent(itemId), {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json()).then(data => {
                if (data && (data.success ?? false)) {
                    // eliminar fila
                    const tr = btn.closest('tr');
                    if (tr) tr.remove();
                } else alert(data.message || 'Error');
            }).catch(() => alert('Error al eliminar'));
    }

    // Manejo para añadir ítem a pedido existente vía AJAX
    document.addEventListener('click', function(e){
        if (!e.target) return;
        if (e.target.classList.contains('add-existing')) {
            const tr = e.target.closest('tr');
            const productId = tr.querySelector('.product-select').value;
            const qty = tr.querySelector('input[name="quantity[]"]').value;
            const price = tr.querySelector('input[name="price[]"]').value;
            const discount = tr.querySelector('input[name="discount[]"]').value || 0;
            if (!productId || !existingOrderId) { alert('Seleccione producto.'); return; }
            const fd = new FormData();
            fd.append('order_id', existingOrderId);
            fd.append('product_id', productId);
            fd.append('quantity', qty);
            fd.append('price', price);
            fd.append('discount', discount);
            fetch('items.php', { method: 'POST', credentials: 'same-origin', body: fd })
            .then(r => r.json()).then(data => {
                if (data && (data.success ?? false)) {
                    // reemplazar fila de entrada por fila de ítem existente (con delete)
                    const newTr = document.createElement('tr');
                    const name = tr.querySelector('.product-select').selectedOptions[0].textContent || '';
                    newTr.innerHTML = `<td>${name}<input type="hidden" name="product_id[]" value="${productId}"></td><td><input type="number" name="quantity[]" class="form-control form-control-sm" value="${qty}" min="1" disabled></td><td><input type="number" step="0.01" name="price[]" class="form-control form-control-sm" value="${price}" disabled></td><td><input type="number" step="0.01" name="discount[]" class="form-control form-control-sm" value="${discount}" disabled></td><td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteItem(${data.data.order_items_id}, this)"><i class="bi bi-trash"></i></button></td>`;
                    tr.replaceWith(newTr);
                } else alert(data.message || 'Error al añadir');
            }).catch(()=>alert('Error al añadir'));
        }
    });
</script>