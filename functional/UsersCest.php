<?php
namespace Users;

use \FunctionalTester;

class UsersCest
{
    private $I;

    // tests
    public function tryCheckGET(FunctionalTester $I)
    {
        $this->I = $I;

        $I->wantTo('Check GET /users/api/users');

        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendGET('/users/api/users');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse(), true);

        foreach ($response as $user) {
            $this->assertApiUser($user);
        }
    }

    private function assertApiUser($user)
    {
        return $this->I->assertJsonStructure(
            '{
                "user_id": <integer>,
                "surname": <string|null>,
                "name": <string>,
                "patronymic": <string|null>,
                "username": <string>,
                "country_id": <integer>,
                "language_id": <integer|null>,
                "birth_date": <dateTime>,
                "email_main": <string>,
                "gender_id": <integer>,
                "invitation_code": <string|null>,
                "created_date": <dateTime>,
                "created_by": <integer|null>,
                "changed_by": <integer|null>,
                "changed_date": <dateTime>,
                "is_approved": <boolean|null>
            }',
            $user);
    }


}
