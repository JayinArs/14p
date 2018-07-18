<?php

namespace App\Http\Controllers;

use App\Member;
use Illuminate\Http\Request;
use Datatables;
use Validator;

class MemberController extends Controller
{
	public function index()
	{
		return view( 'member.index' );
	}

	public function data()
	{
		return Datatables::of( Member::all() )->make( true );
	}

	public function edit( Member $member )
	{
		return view( 'member.edit', [
			'member' => $member
		] );
	}

	public function update( Member $member, Request $request )
	{
		$data  = $request->only( [
			                         'first_name',
			                         'last_name',
			                         'email',
			                         'age_group',
			                         'gender',
			                         'country',
			                         'city',
			                         'state',
			                         'street'
		                         ] );
		$rules = [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|unique:members,email,' . $member->id,
			'country'    => 'required',
			'gender'     => 'required',
			'city'       => 'required_without:state',
			'state'      => 'required_without:city'
		];

		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			return response()->json( [
				                         'status' => 'danger',
				                         'errors' => $validator->getMessageBag()->toArray(),
			                         ], 400 );
		}

		$member->fill( $data );

		if ( $member->save() ) {
			return response()->json( [
				                         'status'  => 'success',
				                         'message' => 'Member updated successfully',
			                         ] );
		}

		return response()->json( [
			                         'status'  => 'danger',
			                         'message' => 'Failed to member question',
		                         ] );
	}

	public function destroy( Member $member, Request $request )
	{
		$member->delete();

		if ( $request->has( 'redirect' ) ) {
			return redirect( $request->get( 'redirect' ) );
		}

		return response()->redirectToRoute( 'member.index' );
	}
}
