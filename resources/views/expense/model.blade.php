<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("المصروفات")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('receptionist.expense.save')}}">
                    @csrf
                    @if(auth()->user()->isadmin)
                        <div class="form-group">
                            <label for="name">@lang("موظف الاستقبال")</label>
                            <select name="receptionist_id" id="receptionist_id" class="form-control select2">
                                @foreach($receptionists as $receptionist)
                                    <option value="{{ $receptionist->id }}">{{ $receptionist->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name">@lang("الاسم")</label>
                        <input required type="text" id="name" name="name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="amount">@lang("القيمة")</label>
                        <input required type="number" id="amount" name="amount" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="note">@lang("ملاحظة")</label>
                        <textarea  type="text" id="note" name="note" class="form-control"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">@lang("اغلاق")</button>
                        <button type="submit" class="btn mb-2 btn-primary">@lang("حفظ")</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>




