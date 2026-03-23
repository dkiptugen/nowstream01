@extends('Backend.includes.layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1">Events</h1>
                    <div class="text-muted">Add Event</div>
                </div>
            </div>
            <div class="card shadow-lg border">
                <div class="card-body">
                    <form action="{{ route('backend.event.store') }}" class="form create-form"
                          enctype="multipart/form-data" method="post"> @csrf
                        <div class="mb-3"><label for="event_name" class="form-label"> Event Name</label> <input
                                type="text" name="event_name" id="event_name" class="form-control form-control-sm">
                        </div>
                        <div class="mb-3"><label for="event_description" class="form-label">Description</label>
                            <textarea name="event_description" id="event_description" class="form-control"
                                     data-ckeditor data-ckeditor-height="250px"></textarea></div>
                        <div class="row g-3 mb-3">
                            <div class="col"><label for="thumbnail" class="form-label">Event Image/ Flyer</label> <input
                                    type="file" name="thumbnail" id="thumbnail_image" class="form-control"
                                    accept="image/*"></div>
                            <div class="col"><label for="stream_thumbnail" class="form-label">Stream Thumbnail</label>
                                <input type="file" name="stream_thumbnail" id="stream_thumbnail" class="form-control"
                                       accept="image/*"></div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col"><label for="publishdate" class="form-label">Publish Date</label> <input
                                    type="text" name="publishdate" id="publishdate" class="form-control datesingle">
                            </div>
                            <div class="col"><label for="event_time" class="form-label"> Event Time</label> <input
                                    type="text" name="event_time" id="event_time" class="form-control datetimes"></div>
                        </div>
                        <div class="mb-3"><label for="venue" class="form-label"> Venue</label> <input type="text"
                                                                                                      name="venue"
                                                                                                      id="venue"
                                                                                                      class="form-control">
                        </div> <!-- TICKETS -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check mb-2"><input type="checkbox" id="hasTickets" name="has_tickets" value="1"
                                                                    class="form-check-input"
                                                                    aria-controls="ticketsContainer"
                                                                    aria-expanded="false"> <label for="hasTickets"
                                                                                                  class="form-check-label">Has
                                        Tickets</label></div>
                                <button type="button" id="addTicketBtn" class="btn btn-link text-decoration-none mb-2"
                                        style="display:none;" aria-controls="ticketsContainer"><i class="fas fa-plus"
                                                                                                  aria-hidden="true"></i>
                                    Ticket
                                </button>
                            </div>
                            <div id="ticketsContainer" aria-live="polite" aria-label="Ticket options"></div>
                        </div> <!-- STREAMS -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check mb-2"><input type="checkbox" id="hasStream" name="has_stream" value="1"
                                                                    class="form-check-input"
                                                                    aria-controls="streamsContainer"
                                                                    aria-expanded="false"> <label for="hasStream"
                                                                                                  class="form-check-label">Has
                                        Stream</label></div>
                                <button type="button" id="addStreamBtn" class="btn btn-link text-decoration-none mb-2"
                                        style="display:none;" aria-controls="streamsContainer"><i class="fas fa-plus"
                                                                                                  aria-hidden="true"></i>
                                    Stream Price
                                </button>
                            </div>
                            <div id="streamsContainer" aria-live="polite" aria-label="Stream pricing options"></div>
                        </div> <!-- MERCHANDISE -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check mb-2"><input type="checkbox" id="hasMerch" name="has_merch" value="1"
                                                                    class="form-check-input"
                                                                    aria-controls="merchContainer"
                                                                    aria-expanded="false"> <label for="hasMerch"
                                                                                                  class="form-check-label">Has
                                        Merchandise</label></div>
                                <button type="button" id="addMerchBtn" class="btn btn-link text-decoration-none mb-2"
                                        style="display:none;" aria-controls="merchContainer"><i class="fas fa-plus"
                                                                                                aria-hidden="true"></i>
                                    Merchandise
                                </button>
                            </div>
                            <div id="merchContainer" aria-live="polite" aria-label="Merchandise options"></div>
                        </div>
                        <div class="mb-3"><label class="form-check form-check-inline"> <input class="form-check-input"
                                                                                              type="checkbox"
                                                                                              name="featured" value="1">
                                <span class="form-check-label">Is Featured</span> </label></div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" name="" id="" class="btn btn-sm btn-primary">Add Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('header')
@endsection
@section('footer')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const defaultCurrency = 'USD';
            const merchContainer = document.getElementById('merchContainer');

            function buildVariantRow(merchIndex, variantIndex, variant = {}) {
                return `
                    <div class="variant-row row g-2 align-items-end mb-2">
                        <div class="col-md-5">
                            <label class="form-label">Variant #${variantIndex} Name</label>
                            <input type="text" name="merch[variants][${merchIndex}][name][]" class="form-control" value="${variant.name || ''}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Price Override</label>
                            <input type="number" name="merch[variants][${merchIndex}][price_override][]" class="form-control" step="0.01" min="0" value="${variant.price_override || ''}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="merch[variants][${merchIndex}][stock_total][]" class="form-control" min="0" value="${variant.stock_total || ''}">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger removeVariantBtn">X</button>
                        </div>
                    </div>
                `;
            }

            function buildMerchRow(index) {
                return `
                    <div class="form-group border p-3 mb-3 merch-row" data-merch-index="${index}">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label">Merch #${index + 1} Name</label>
                                <input type="text" name="merch[name][]" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Currency</label>
                                <input type="text" name="merch[currency][]" class="form-control" value="${defaultCurrency}" maxlength="3">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Price</label>
                                <input type="number" name="merch[price][]" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="merch[image][]" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger removeMerchBtn w-100">Remove</button>
                            </div>
                        </div>
                        <div class="border rounded p-3 mt-3 bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Variants</strong>
                                <button type="button" class="btn btn-sm btn-outline-primary addVariantBtn">Add Variant</button>
                            </div>
                            <div class="variantContainer"></div>
                        </div>
                    </div>
                `;
            }

            const sections = [{
                checkboxId: 'hasTickets',
                buttonId: 'addTicketBtn',
                containerId: 'ticketsContainer',
                removeClass: 'removeTicketBtn',
                buildRow(index) {
                    return ` <div class="form-group row border p-2 mb-2"> <div class="col"> <label class="form-label">Ticket #${index} Type</label> <input type="text" name="ticket[type][]" class="form-control"> </div> <div class="col"> <label class="form-label">Quantity</label> <input type="number" name="ticket[quantity][]" class="form-control"> </div> <div class="col"> <label class="form-label">Currency</label> <input type="text" name="ticket[currency][]" class="form-control" value="${defaultCurrency}" maxlength="3"> </div> <div class="col"> <label class="form-label">Cost</label> <input type="number" name="ticket[cost][]" class="form-control" step="0.01" min="0"> </div> <div class="col-auto"> <button type="button" class="btn btn-danger removeTicketBtn mt-4">Remove</button> </div> </div> `;
                }
            }, {
                checkboxId: 'hasStream',
                buttonId: 'addStreamBtn',
                containerId: 'streamsContainer',
                removeClass: 'removeStreamBtn',
                buildRow(index) {
                    return ` <div class="form-group row border p-2 mb-2"> <div class="col"> <label class="form-label">Stream #${index} Rate Name</label> <input type="text" name="stream[rate_name][]" class="form-control"> </div> <div class="col"> <label class="form-label">Currency</label> <input type="text" name="stream[currency][]" class="form-control" value="${defaultCurrency}" maxlength="3"> </div> <div class="col"> <label class="form-label">Price</label> <input type="number" name="stream[price][]" class="form-control" step="0.01" min="0"> </div> <div class="col-auto"> <button type="button" class="btn btn-danger removeStreamBtn mt-4">Remove</button> </div> </div> `;
                }
            }, {
                checkboxId: 'hasMerch',
                buttonId: 'addMerchBtn',
                containerId: 'merchContainer',
                removeClass: 'removeMerchBtn',
                buildRow(index) {
                    return buildMerchRow(index - 1);
                }
            }];

            sections.forEach(function (section) {
                const checkbox = document.getElementById(section.checkboxId);
                const button = document.getElementById(section.buttonId);
                const container = document.getElementById(section.containerId);
                if (!checkbox || !button || !container) {
                    return;
                }
                const syncVisibility = function () {
                    const isExpanded = checkbox.checked;
                    checkbox.setAttribute('aria-expanded', String(isExpanded));
                    button.style.display = isExpanded ? '' : 'none';
                    if (!isExpanded) {
                        container.innerHTML = '';
                        if (container === merchContainer) {
                            merchContainer.dataset.nextIndex = '0';
                        }
                    }
                };
                checkbox.addEventListener('change', syncVisibility);
                syncVisibility();
                button.addEventListener('click', function () {
                    if (container === merchContainer) {
                        const nextIndex = Number(merchContainer.dataset.nextIndex || '0');
                        container.insertAdjacentHTML('beforeend', buildMerchRow(nextIndex));
                        merchContainer.dataset.nextIndex = String(nextIndex + 1);
                        return;
                    }
                    const index = container.querySelectorAll('.form-group').length + 1;
                    container.insertAdjacentHTML('beforeend', section.buildRow(index));
                });
                container.addEventListener('click', function (event) {
                    const removeButton = event.target.closest('.' + section.removeClass);
                    if (removeButton) {
                        const row = removeButton.closest('.form-group');
                        if (row) {
                            row.remove();
                        }
                        return;
                    }
                });
            });

            merchContainer?.addEventListener('click', function (event) {
                const merchRow = event.target.closest('.merch-row');
                if (!merchRow) {
                    return;
                }

                if (event.target.closest('.addVariantBtn')) {
                    const merchIndex = merchRow.dataset.merchIndex;
                    const variantContainer = merchRow.querySelector('.variantContainer');
                    const variantIndex = variantContainer.querySelectorAll('.variant-row').length + 1;
                    variantContainer.insertAdjacentHTML('beforeend', buildVariantRow(merchIndex, variantIndex));
                    return;
                }

                const removeVariantButton = event.target.closest('.removeVariantBtn');
                if (removeVariantButton) {
                    removeVariantButton.closest('.variant-row')?.remove();
                }
            });
        });
    </script>
@endsection
