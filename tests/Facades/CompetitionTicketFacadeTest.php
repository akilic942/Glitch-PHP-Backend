<?php
namespace Tests\Facades;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\CompetitionTicketFacade;
use App\Facades\PermissionsFacade;
use App\Models\Competition;
use App\Models\CompetitionInvite;
use App\Models\CompetitionTicketType;
use App\Models\CompetitionTicketTypeField;
use App\Models\User;
use Tests\TestCase;

class CompetitionTicketFacadeTest extends TestCase {


    public function testValidateFields() {

        $type1 = CompetitionTicketType::factory()->create();

        $type2 = CompetitionTicketType::factory()->create();

        $type1_field1_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type1, 'is_required' => 1]);

        $type1_field2_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type1, 'is_required' => 1]);

        $type1_field3_not_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type1]);

        $type2_field1_not_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type2]);

        $type2_field2_not_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type2]);

        $result = CompetitionTicketFacade::validateFields($type1, []);

        $this->assertFalse($result['status']);

        $this->assertCount(2, $result['errors']);

        $data = [];
        $data[$type1_field1_required->name] = 'sfsdfsdsds';
        $data[$type1_field2_required->name] = 'klcmvsdd';

        $result = CompetitionTicketFacade::validateFields($type1, $data);

        $this->assertTrue($result['status']);

        $result = CompetitionTicketFacade::validateFields($type2, []);

        $this->assertTrue($result['status']);
    }
}