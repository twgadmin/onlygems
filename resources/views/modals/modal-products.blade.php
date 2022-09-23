<div class="modal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Choose the variants of "<span class="product-title"></span>" to add to the order</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table table-striped product-details-modal-table">
                <thead>
                    <tr class="col">
                        <th><input class="check-all-products" type="checkbox"></th>
                        <th>Variant Name</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Cost Price</th>
                        <th>Total Cost</th>
                    </tr>
                </thead>
                <tbody class="products-modal-tbody">

                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <span class="variants-selected-text">No variant(s) have been selected</span>
            <button type="button" disabled class="btn btn-primary add-variants-to-order">Add Variants to Order</button>
          {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
        </div>
      </div>
    </div>
  </div>
