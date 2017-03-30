@if ($field->width()->getNameSce() && $field->height()->getNameSce())
    <?php
    $error1 = 'page.errors[\'' . $field->width()->getNameSce().'\']';
    $error2 = 'page.errors[\'' . $field->height()->getNameSce().'\']';
    $errorMsg = '{{' . $error1 . '[0]||' . $error2 . '[0]}}';
    ?>
    <small class="form-control-feedback" ng-show="{{$error1}}||{{$error2}}">{{$errorMsg}}</small>
@endif