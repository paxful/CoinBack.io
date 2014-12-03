<div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgot-modal-label" aria-hidden="true">
    <a href="#" class="pclose" data-dismiss="modal"></a>
    <div class="divtable">
        <div class="divcell">
            <article class="container text-center">
                <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2 bill-cards-container">
                    <div class="icon" data-icon="Z"></div>
                    <h2>forgot password</h2>
                    <form action="{{ action('RemindersController@postRemind') }}" id="resetForm" role="form" method="POST">
                        <div id="reminder-modal-form-message"></div>
                        <div class="form-group">
                            {{ Form::label('forgotPassEmail', 'Email', array('class' => 'sr-only'))}}
                            {{ Form::email('forgotPassEmail', null, array('class' => 'form-control', 'id' => 'forgotPassEmail', 'placeholder' => 'Email')) }}
                        </div>
                        <button id="btn-reset-pass-modal" class="btn btn-default btn-block ladda-button" data-style="zoom-in" type="submit">
                            <span class="ladda-label">Get Password</span>
                        </button>
                        <p><a href="#" data-toggle="modal" data-dismiss="modal" data-target="#loginModal">Login</a></p>
                    </form>
                </div>
            </article>
        </div>
    </div>
</div>