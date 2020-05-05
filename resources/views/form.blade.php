<div class="modal fade" role="dialog">
    <div class="modal-dialog {!! $size !!}" role="document">
        <div class="modal-content">
            <div class="modal-header box-header with-border">
                <h3 class="box-title">{{ $form->title() }}</h3>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin: 5px 0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {!! $form->renderTools() !!}
                </div>
            </div>
            {!! $form->open(['class' => "form-horizontal"]) !!}
            <div class="modal-body box-body">
                @if(!$tabObj->isEmpty())
                    @include('admin::form.tab', compact('tabObj'))
                @else
                    <div class="fields-group">

                        @if($form->hasRows())
                            @foreach($form->getRows() as $row)
                                {!! $row->render() !!}
                            @endforeach
                        @else
                            @foreach($layout->columns() as $column)
                                <div class="col-md-{{ $column->width() }}">
                                    @foreach($column->fields() as $field)
                                        {!! $field->render() !!}
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <div class="btn-group pull-left" style="margin-right: 5px">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
                {!! $form->renderFooter() !!}

                @foreach($form->getHiddenFields() as $field)
                    {!! $field->render() !!}
                @endforeach

                <!-- /.box-footer -->
            </div>
            {!! $form->close() !!}
        </div>
    </div>
    {!! Modal::script() !!}
</div>
