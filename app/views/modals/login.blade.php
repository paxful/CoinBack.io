<div class="modal fade" id="loginModal">
    <a href="#" class="pclose" data-dismiss="modal"></a>
    <div class="divtable">
        <div class="divcell">
            <article class="container text-center">
                <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2 bill-cards-container">
                    <div class="icon" data-icon="Z"></div>
                    <h2>login</h2>
                    {{ Form::open(array('url' => 'home/login', 'role' => 'form', 'id' => 'loginForm')); }}
                        <div id="login-modal-form-message">@if (Session::has('password_reset')) {{Session::get('password_reset')}} @endif</div>
                        <div class="form-group">
                            <input name="loginEmail" id="loginEmail" class="form-control text-center" type="text" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input name="password" id="password" class="form-control text-center" type="password" placeholder="Password">
                        </div>
                        <button id="btn-login-modal" class="btn btn-default btn-block ladda-button" data-style="zoom-in" type="submit">
                            <span class="ladda-label">Log in</span>
                        </button>
                        <p class="form-control-static"><a href="#" data-toggle="modal" data-dismiss="modal" data-target="#forgotModal">{{trans('web.lost_password')}}</a></p>
                    {{Form::close()}}
                </div>
            </article>
        </div>
    </div>
</div>