<?php

class ShareCardController extends BaseController{

	public function requestExchange($id_user){
		if(isset($id_user)){
			if($id_user != Session::get('id')){
				$id_user_session = Session::get('id');
				$repeat1 = ShareCard::where('id_userRequest', '=', $id_user_session)
									->where('id_userResponse', '=', $id_user)->first();

				$repeat2 = ShareCard::where('id_userRequest', '=', $id_user)
									->where('id_userResponse', '=', $id_user_session)->first();

				if($repeat1 || $repeat2){
					return Response::json(array(
					'reason' => 'request Exchange exist', 'status' => 1))->setCallback(Input::get('callback'));

				}else{
					$request = new ShareCard;
					$request->id_userRequest = $id_user_session;
					$request->id_userResponse = $id_user;
					$request->status = 0;
					$request->save();

					return Response::json(array(
						'reason' => 'request Exchange created', 'status' => 0))->setCallback(Input::get('callback'));
				}

			}
			return Response::json(array(
				'reason' => 'the ID is the same as of the session', 'status' => 2))->setCallback(Input::get('callback'));
		}
		return Response::json(array(
			'reason' => 'empty field', 'status' => 3))->setCallback(Input::get('callback'));
	}   

	public function responseExchange($share_card_id, $status){

		if(isset($share_card_id) && isset($status) && is_numeric($status) 
			&& (intval($status) <= 2 && intval($status) >= 0)){

			$card = ShareCard::where('_id', '=', $share_card_id)
							 ->where('id_userResponse', '=', Session::get('id'))->first();
			if($card){

				$stts = $card->status;
				if($stts == 0){

					$card->status = intval($status);
					$card->save();

					return Response::json(array(
						'reason' => 'status changed', 'status' => 0))->setCallback(Input::get('callback'));
				}
				return Response::json(array(
					'reason' => 'status had been changed', 'status' => 1))->setCallback(Input::get('callback'));
			}
			return Response::json(array(
				'reason' => 'invalid ID share card', 'status' => 2))->setCallback(Input::get('callback'));
		}
		return Response::json(array(
			'reason' => 'fields arguments are icorrect', 'status' => 3))->setCallback(Input::get('callback'));
	}

	public function getCardsToResponse(){
		$cards = ShareCard::where('id_userResponse', '=', Session::get('id'))
						  ->where('status', '=', 0)->get();

		if($cards){
			foreach ($cards as $card) {
				unset($card['id_userResponse']);
			}
			return Response::json(array(
				'reason' => 'requests founds', 'status' => 0, 'requests' => $cards))->setCallback(Input::get('callback'));
		}
		return Response::json(array(
			'reason' => 'you haven´t requests', 'status' => 1))->setCallback(Input::get('callback'));
	}

	public function getMyCards(){
		$cardsRequest = ShareCard::where('status', '=', 1)
								 ->where('id_userRequest', '=', Session::get('id'))->get();

		$cardsResponse = ShareCard::where('status', '=', 1)
								  ->where('id_userResponse', '=', Session::get('id'))->get();
		$list = array();		
		foreach ($cardsRequest as $card) {
			array_push($list, $card->id_userResponse);
		}

		foreach ($cardsResponse as $card) {
			array_push($list, $card->id_userRequest);
		}

		$cards = User::whereIn('_id', $list)->lists('card');

		if($cards){
			return Response::json(array(
				'reason' => 'you have cards', 'status' => 0, 'cards' => $cards))->setCallback(Input::get('callback'));
		}
		return Response::json(array(
			'reason' => 'you don´t have cards', 'status' => 1))->setCallback(Input::get('callback'));
	}
}