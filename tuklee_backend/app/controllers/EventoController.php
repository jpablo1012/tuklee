<?php

class EventoController extends BaseController {
    /**
     * beginDate, endDate  date format is YYYY-MM-DD
     * 
     */
    public function create() {
        if (Input::has('name') && Input::has('hastag') && Input::has('beginDate') && Input::has('endDate') && Input::has('description')) {
            $comprobate = Evento::where('name', '=', Input::get('name'))->first();
            $comprobate2 = Evento::where('hastag', '=', Input::get('hastag'))->first();
            if ($comprobate == null && $comprobate2 == null) {
                $event = new Evento;
                $event->name = Input::get('name');
                $event->hastag = Input::get('hastag');
                $event->beginDate = Input::get('beginDate');
                $event->endDate = Input::get('endDate');
                $event->description = Input::get('description');
                $event->ubication = EventoController::exist();
                $event->usersList = array();
                $event->save();
                return Response::json(array(
                            'reason' => 'Event add succesfully', 'status' => 0))->setCallback(Input::get('callback'));
            }
            return Response::json(array(
                        'reason' => 'Event already created', 'status' => 1))->setCallback(Input::get('callback'));
        }
        return Response::json(array(
                    'reason' => 'Some field empty or invalid', 'status' => 2))->setCallback(Input::get('callback'));
    }

    public function exist() {
        if (Input::get('latitude') && Input::get('longitude')) {
            return array('latitude' => Input::get('latitude'), 'longitude' => Input::get('longitude'));
        }
        return null;
    }

    public function getInfoEvent($id_event) {
        $event = Evento::where('_id', '=', $id_event)->first();

        if ($event) {
            unset($event['usersList']);
            return Response::json(
                            array('reason' => 'event information', 'status' => 0, 'event' => $event))->setCallback(Input::get('callback'));
        }
        return Response::json(array(
                    'reason' => 'envent not exist', 'status' => 1))->setCallback(Input::get('callback'));
    }

    public function enterEvent() {
        if (Input::get('hastag') && Session::get('id') && Input::get('hastags')) {
            $hastag = Input::get('hastag');
            $user_id = Session::get('id');
            $hastags = EventoController::hastagsToArray(Input::get('hastags'));
            $comprobate2 = Evento::where('hastag', '=', $hastag)->first();
            if ($comprobate2) {
                if (EventoController::onEvento($comprobate2->_id) == "true") {
                    return Response::json(array('reason' => 'already registered user', 'status' => 4))->setCallback(Input::get('callback'));
                }
                $today = date("Ymd");
                $beginDate = str_replace('-', '', $comprobate2->beginDate);
                $endDate = str_replace('-', '', $comprobate2->endDate);
                if ($beginDate <= $today && $endDate >= $today) {
                    $array = array('user_id' => $user_id, 'hastags' => $hastags, 'state' => 'active');
                    $arraydb = $comprobate2->usersList;
                    array_push($arraydb, $array);
                    $comprobate2->usersList = $arraydb;
                    $comprobate2->save();
                    $user = User::where('_id', '=', $user_id)->first();
                    EventoController::deleteUserOfEvent();
                    $user->activeEvent = $comprobate2->_id;
                    $user->save();
                    return Response::json(array('reason' => 'You have entered the event', 'status' => 0))->setCallback(Input::get('callback'));
                }
                return Response::json(array('reason' => 'the event has not started or It has finished ', 'status' => 1))->setCallback(Input::get('callback'));
            }
            return Response::json(array('reason' => 'inexistent hastag', 'status' => 2))->setCallback(Input::get('callback'));
        }
        return Response::json(array('reason' => 'empty fields', 'status' => 3))->setCallback(Input::get('callback'));
    }

    public function getListPeopleInsideEvent() {
        $event_id = Input::get('event_id');
        $event = Evento::where('_id', '=', $event_id)->first();
        if ($event) {
            return Response::json(array('reason' => 'users found', 'status' => 0, 'ListPeopleInsideEvent' => $event->usersList))->setCallback(Input::get('callback'));
        }
        return Response::json(array('reason' => 'inexistent Event o invalid id', 'status' => $event))->setCallback(Input::get('callback'));
    }

    public function hastagsToArray($text) {
        $text = str_replace(' ', '', $text);
        $array = explode(",", $text);
        return $array;
    }

    public function onEvento($event_id) {
        $id = Session::get('id');
        $evento = Evento::where('_id', '=', $event_id)->first();
        $users = json_encode($evento->usersList);
        $users2 = json_decode($users);
        foreach ($users2 as $user) {
            if ($user->user_id == $id) {
                return "true";
            }
        }
        return "false";
    }

    public function deleteUserOfEvent() {
        $id = Session::get('id');
        $user = User::where('_id', '=', $id)->first();
        if ($user->activeEvent != null) {
            $evento = Evento::where('_id', '=', $user->activeEvent)->first();
            $users = json_encode($evento->usersList);
            $users2 = json_decode($users);
            foreach ($users2 as $user) {
                if ($user->user_id == $id) {
                    $user->state = 'inactive';
                }
            }
            $evento->usersList = $users2;
            $evento->save();
        }
    }

}
