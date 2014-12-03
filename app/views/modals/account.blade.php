<div class="modal fade" id="accountModal">
    <a href="#" class="pclose" data-dismiss="modal"></a>
    <div class="divtable">
        <div class="divcell">
            <article class="container text-center">
                <div class="col-md-8 col-sm-10 col-md-offset-2 col-sm-offset-1">
                    <div class="row">
                        <div class="col-sm-9 col-sm-offset-3">
                            <div class="icon" data-icon="Z"></div>
                            <h2>Account settings</h2>
                        </div>
                    </div>
                    {{ Form::open(array('url' => 'control/update', 'role' => 'form', 'id' => 'accountForm')); }}
                        <div class="col-sm-9 col-sm-offset-3" id="account-modal-form-message"></div>
                        <div class="form-group">
                            <p class="form-control-static col-sm-9 col-sm-offset-3 white">{{Auth::user()->email}}</p>
                        </div>
                        <div class="form-group">
                            <label for="business_name" class="col-sm-3 control-label">Business name</label>
                            <div class="col-sm-9">
                                {{ Form::text('business_name', Auth::user()->business_name, array('class' => 'form-control', 'id' => 'business_name', 'placeholder' => 'Business name')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-sm-3 control-label">Phone</label>
                            <div class="col-sm-9">
                                {{ Form::text('phone', Auth::user()->phone, array('class' => 'form-control', 'id' => 'phone', 'placeholder' => 'Phone number')) }}
                            </div>
                        </div>
                        {{ Form::hidden('location_id', Auth::user()->location_id, array('id' => 'location_id')) }}
                        <div class="form-group">
                            <label for="address" class="col-sm-3 control-label">Address</label>
                            <div class="col-sm-9">
                                {{ Form::text('address', Auth::user()->address, array('class' => 'form-control', 'id' => 'address', 'placeholder' => 'Address')) }}
                            </div>
                        </div>
                        <div class="form-group country-container">
                            <label for="country" class="col-sm-3 control-label">Country</label>
                            <div class="col-sm-9">
                                {{ Form::select('country', $country, Auth::user()->country_id, array('id' => 'country', 'class' => 'form-control select2')) }}
                            </div>
                        </div>
                        <div class="form-group state-container">
                            <label for="state" class="col-sm-3 control-label">State</label>
                            <div class="col-sm-9">
                                {{ Form::hidden('state', null, array('id' => 'state', 'class' => 'form-control select2')) }}
                            </div>
                        </div>
                        <div class="form-group city-container">
                            <label for="city" class="col-sm-3 control-label">City</label>
                            <div class="col-sm-9">
                                {{ Form::hidden('city', null, array('id' => 'city', 'class' => 'form-control select2')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="post_code" class="col-sm-3 control-label">Postal code</label>
                            <div class="col-sm-9">
                                {{ Form::text('post_code', Auth::user()->post_code, array('class' => 'form-control', 'id' => 'post_code', 'placeholder' => 'Postal code')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tax" class="col-sm-3 control-label">Tax</label>
                            <div class="col-sm-9">
                                {{ Form::number('tax', Auth::user()->tax, array('class' => 'form-control', 'id' => 'tax', 'placeholder' => 'Tax percentage')) }}
                                <span class="help-block">Tax is not added in calculations. Used only for informational purposes.</span>
                            </div>
                        </div>
                        <button id="btn-account-modal" class="btn btn-default col-sm-9 col-sm-offset-3 ladda-button" data-style="zoom-in" type="submit">
                            <span class="ladda-label">Save</span>
                        </button>
                    {{Form::close()}}
                </div>
            </article>
        </div>
    </div>
</div>