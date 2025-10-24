<?php $__env->startSection('title', 'Meu Carrinho'); ?>
<?php $__env->startSection('boxed', content: true); ?>

<?php $__env->startSection('content'); ?>
<h1 class="cart-title">Meu Carrinho</h1>

<?php $total = 0; ?>

<div class="cart-content">
    <div class="cart-table">
    <?php if(empty($cartItems)): ?>
        <p class="empty-cart">O carrinho está vazio.</p>
    <?php else: ?>
        <table class="table">
        <thead>
            <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
            <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr data-item-id="<?php echo e($id); ?>">
                <td>
                <img src="<?php echo e(asset('storage/' . $item['photo'])); ?>" alt="<?php echo e($item['name']); ?>"
                        style="width: 50px; height: 50px; border-radius: 5px;">
                <?php echo e($item['name']); ?>

                </td>
                <td>R$ <?php echo e(number_format($item['price'], 2, ',', '.')); ?></td>
                <td class="quantity-controls">
                <div class="input-group" style="max-width: 120px;">
                    <button class="btn btn-outline-secondary btn-sm btn-decrease" type="button">-</button>
                    <input type="text" class="form-control text-center quantity-input"
                            value="<?php echo e($item['quantity']); ?>" readonly>
                    <button class="btn btn-outline-secondary btn-sm btn-increase" type="button">+</button>
                </div>
                </td>
                <td class="subtotal">R$ <?php echo e(number_format($subtotal, 2, ',', '.')); ?></td>
                <td>
                <button type="button" class="btn btn-danger btn-sm delete-button"
                        data-item-id="<?php echo e($id); ?>" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    Remover
                </button>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        </table>
    <?php endif; ?>
    </div>

    <div class="cart-summary">
    <h4>Resumo</h4>
    <p>Valor dos produtos: <span id="total-value">R$ <?php echo e(number_format($total, 2, ',', '.')); ?></span></p>
    <p>Entrega: <span>Retirada com o produtor</span></p>
    <p>Desconto: <span id="discount-value">R$ 0,00</span></p>
    <hr>
    <p><strong>Total: <span id="grand-total">R$ <?php echo e(number_format($total, 2, ',', '.')); ?></span></strong></p>
    <button class="btn btn-success btn-finalize" id="finalizarPedido">
        <span id="finalizarText">Finalizar Pedido</span>
        <span id="finalizarSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    </button>
    <a class="btn btn-dark btn-finalize mt-2" href="<?php echo e(route('products.show')); ?>">Continuar Comprando</a>
    </div>
</div>

  
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirmação</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Tem certeza de que deseja remover este item do carrinho?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger confirm-delete">Remover</button>
        </div>
      </div>
    </div>
  </div>

  
  <div class="modal fade" id="finalizarModal" tabindex="-1" aria-labelledby="finalizarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="finalizarModalLabel">Pedido Finalizado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Pedido realizado com sucesso!</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
  /* se o layouts.app já define o background, pode remover este trecho */
  body {
    background-image: url('<?php echo e(asset("images/background2.jpg")); ?>');
    background-size: cover;
    background-position: center;
    min-height: 100vh;
  }

  .cart-container {
    width: 1000px;
    margin: 24px auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,.1);
  }

  .cart-title {
    font-size: 2rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 20px;
    color: #333;
  }

  .cart-content {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
  }

  .cart-table {
    flex: 2;
    background: rgba(255,255,255,.9);
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,.1);
  }

  .cart-summary {
    flex: 1;
    background: #f5f5f5;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,.1);
  }

  .cart-summary h4 { font-weight: 700; color: #333; margin-bottom: 15px; }
  .cart-summary .btn-finalize { margin-top: 12px; width: 100%; }
  .empty-cart { text-align: center; font-size: 1.1rem; color: #555; margin: 12px 0; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // CSRF do <meta> que já deve estar no layouts.app
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    let currentItemId = null;

    document.querySelectorAll('.delete-button').forEach(button => {
      button.addEventListener('click', function () {
        currentItemId = this.getAttribute('data-item-id');
      });
    });

    const confirmDeleteBtn = document.querySelector('.confirm-delete');
    if (confirmDeleteBtn) {
      confirmDeleteBtn.addEventListener('click', function () {
        if (!currentItemId) return;

        fetch("<?php echo e(route('cart.delete')); ?>", {
          method: "DELETE",
          headers: {
            "X-CSRF-TOKEN": csrf,
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ item_id: currentItemId })
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            const row = document.querySelector(`button[data-item-id="${currentItemId}"]`)?.closest('tr');
            if (row) row.remove();
            updateCartTotal();
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal?.hide();

            // Se zerou tudo, mostra texto de vazio
            if (document.querySelectorAll('.cart-table tbody tr').length === 0) {
              document.querySelector('.cart-table').innerHTML = '<p class="empty-cart">O carrinho está vazio.</p>';
            }
          }
        })
        .catch(err => console.error('Erro ao remover o item:', err));
      });
    }

    const finalizarBtn = document.getElementById('finalizarPedido');
    finalizarBtn?.addEventListener('click', function () {
      const spinner = document.getElementById('finalizarSpinner');
      const finalizarText = document.getElementById('finalizarText');

      spinner.style.display = 'inline-block';
      finalizarText.style.display = 'none';
      finalizarBtn.disabled = true;

      fetch("<?php echo e(route('cart.finalizar')); ?>", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": csrf }
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const modal = new bootstrap.Modal(document.getElementById('finalizarModal'));
          modal.show();

          // Limpa a tabela
          document.querySelectorAll('.cart-table tbody tr').forEach(row => row.remove());
          updateCartTotal();
          document.querySelector('.cart-table').innerHTML = '<p class="empty-cart">O carrinho está vazio.</p>';
        } else {
          alert('Houve um erro ao finalizar o pedido.');
        }
      })
      .catch(err => {
        console.error('Erro ao finalizar o pedido:', err);
        alert('Houve um erro ao finalizar o pedido.');
      })
      .finally(() => {
        spinner.style.display = 'none';
        finalizarText.style.display = 'inline-block';
        finalizarBtn.disabled = false;
      });
    });

    document.querySelectorAll('.btn-increase, .btn-decrease').forEach(button => {
      button.addEventListener('click', function () {
        const row = this.closest('tr');
        const itemId = row.getAttribute('data-item-id');
        const quantityInput = row.querySelector('.quantity-input');
        let quantity = parseInt(quantityInput.value);
        const isIncrease = this.classList.contains('btn-increase');

        if (isIncrease) quantity += 1;
        else if (quantity > 1) quantity -= 1;

        quantityInput.value = quantity;
        updateSubtotal(row, quantity);

        fetch(`<?php echo e(url('cart/update')); ?>/${itemId}`, {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": csrf,
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ item_id: itemId, quantity })
        })
        .then(r => r.json())
        .then(data => {
          if (!data.success) alert('Erro ao atualizar o carrinho. Tente novamente.');
        })
        .catch(err => {
          console.error('Erro ao atualizar a quantidade:', err);
          alert('Erro ao atualizar o carrinho. Verifique sua conexão.');
        });
      });
    });

    function updateSubtotal(row, quantity) {
      const price = parseFloat(
        row.querySelector('td:nth-child(2)').innerText.replace('R$ ', '').replace('.', '').replace(',', '.')
      );
      const subtotalElement = row.querySelector('.subtotal');
      const subtotal = (price * quantity).toFixed(2);
      subtotalElement.innerText = `R$ ${subtotal.replace('.', ',')}`;
      updateCartTotal();
    }

    function updateCartTotal() {
      let total = 0;
      document.querySelectorAll('.subtotal').forEach(el => {
        const val = parseFloat(el.innerText.replace('R$ ', '').replace('.', '').replace(',', '.'));
        if (!isNaN(val)) total += val;
      });
      const totalText = `R$ ${total.toFixed(2).replace('.', ',')}`;
      const totalValue = document.getElementById('total-value');
      const grandTotal = document.getElementById('grand-total');
      if (totalValue) totalValue.innerText = totalText;
      if (grandTotal) grandTotal.innerText = totalText;
    }
  });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\cart\view.blade.php ENDPATH**/ ?>