<?php

class UserController extends BaseController {

    /**
     * fields (* obligatory): username*,name*, email*, password*
     * @return type json: status-> 0: sucessfull, 1:existent user, 2: empty field
     */
    public function register(){
        if(Input::has('username') && Input::has('name') && Input::has('email') && Input::has('password')){

            $comprobate = DB::collection('users')->where('username', Input::get('username'))->orWhere('email', Input::get('email'))->first();

                        
            if($comprobate == null){
                $user = new User;
                $user->username = Input::get('username');
                $user->name = Input::get('name');
                $user->email = Input::get('email');
                $user->password = Hash::make(Input::get('password'));
                $user->isCompleted = false;
                $user->card = null;
                $user->activeEvent = null;

                $user->save();

                return Response::json(array(
                    'reason' => 'user add succesfully', 'status' => 0 ))->setCallback(Input::get('callback'));
            }
            return Response::json(array(
                'reason' => 'username or email are being used', 'status' => 1 ))->setCallback(Input::get('callback'));
        }

        return Response::json(array(
            'reason' => 'fields arguments are icorrect', 'status' => 2 ))->setCallback(Input::get('callback'));
    }
    
    /**
     * fields (* obligatory): username*, password*
     * @return type json: status-> 0: sucessfull, 1:inexistent user//invalid password, 2:inexistent user//invalid username, 3:empty field
     */
    public function login($username, $password) {
        if (isset($username) && isset($password)) {
            $user = User::where('username', '=', $username)->first();
            if (($user)) {
                if (Hash::check($password, $user->password)) {
                    Session::put('id', $user->_id);
                    Session::put('username', $username);
                    return Response::json(
                        array('reason' => 'correct validation', 'status' => 0))->setCallback(Input::get('callback'));
                }
                return Response::json(
                    array('reason' => 'invalid or inexistent username or password ', 'status' => 1))->setCallback(Input::get('callback'));
            }
            return Response::json(
                array('reason' => 'invalid or inexistent username or password', 'status' => 2))->setCallback(Input::get('callback'));
        }
        return Response::json(
            array('reason' => 'empty field', 'status' => 3))->setCallback(Input::get('callback'));
    }
    
    /**
     * 
     * @return type json: status-> 0: is Completed, 1:uncompleted, 2:inexistent user
     */
    public function profileIsCompleted(){
        $id = Session::get('id');
        $user = User::where('_id', '=', $id)->first();
        if($user){
            $isCompleted = $user->isCompleted;
            if($isCompleted){
                return Response::json(
                    array('reason' => 'profile is completed', 'status' => 0 ))->setCallback(Input::get('callback'));
            }
            return Response::json(
                array('reason' => 'profile not completed', 'status' => 1 ))->setCallback(Input::get('callback'));
        }
        return Response::json(
            array('reason' => 'user not exist', 'status' => 2))->setCallback(Input::get('callback'));
    }

    /**
     * fields (* obligatory): name*, email*, photo*, phone*, company*, position*
     * @return type json: status-> 0: sucessfull, 1:existent card, 2:inexistent user, 3:incorrect or empty fields,
     */
    public function createCard(){
        if(Input::has('name') && Input::has('email') && Input::has('phone') && Input::has('company') && Input::has('position')){

            $user = User::where('_id', '=', Session::get('id'))->first();
            if($user){
                $card = $user->card;
                if($card == null){

                    $user->card = array(
                        'name' => Input::get('name'),
                        'email' => Input::get('email'),
                        'photo' => Input::get('photo'),
                        'phone' => Input::get('phone'),
                        'company' => Input::get('company'),
                        'position' => Input::get('position')
                    );
                    $user->isCompleted = true;
                    $user->save();

                    return Response::json(
                        array('reason' => 'card created and profile completed', 'status' => 0))->setCallback(Input::get('callback'));
                }

               return Response::json(
                array('reason' => 'existent user card', 'status' => 1))->setCallback(Input::get('callback'));
            }
            return Response::json(
                array('reason' => 'user not exist', 'status' => 2))->setCallback(Input::get('callback'));
        }
        return Response::json(
            array('reason' => 'fields arguments are icorrect', 'status' => 3))->setCallback(Input::get('callback'));
    }
}

