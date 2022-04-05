<?php

namespace App\Tests\Thermician;

use App\Entity\Project\Project;
use App\Entity\Thermician\Remark;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ThermicianTicketTest extends WebTestCase
{
    public function testHomePageThermicianAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');
    }

    public function testThermicianShowTicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+0');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_show_ticket', [
            'idProject' => $project->getId(),
        ]));
        $this->assertResponseStatusCodeSame(200);
        self::assertRouteSame('thermician_show_ticket');
    }

    public function testThermicianTakeATicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+0');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_take_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_show_my_ticket');
    }

    public function testSecondThermicianTryToPickATicketWhoAlreadyTaked(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin2@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+0');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_take_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_home');
    }

    public function testThermicianShowTicketWhoIsAlreadyTaked(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin2@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+0');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_show_ticket', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('thermician_show_ticket');
    }

    public function testThermicianCreateARemarkOnTakedTicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+0');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_create_remark_ticket', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('thermician_create_remark_ticket');
        $form = $crawler->filter('form[name=remark]')->form([
            'remark[title]' => 'Titre Remark',
            'remark[content]' => 'Content',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');
    }

    public function testThermicianTakeATicketAfterRemarkTheOtherTicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+1');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_take_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_show_my_ticket');
    }

    public function testThermicianCreateRemarkOnNotMyTicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin2@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+1');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_create_remark_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_home');
    }

    public function testUserAcceptRemarkOnProjectTicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'userticket+0@email.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+1');
        $remarkRepository = $entityManager->getRepository(Remark::class);
        /** @var Remark $remark */
        $remark = $remarkRepository->findOneByContent('Content');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('remark_accept', [
            'idProject' => $project->getId(),
            'idRemark' => $remark->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testSecondThermicianTryToPickATicketWhoIsANoPriority(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin2@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+0');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_take_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_home');
    }

    public function testThermcianCreateARemarkOnSecondTicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+1');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_create_remark_ticket', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('thermician_create_remark_ticket');
        $form = $crawler->filter('form[name=remark]')->form([
            'remark[title]' => 'Titre Remark',
            'remark[content]' => 'Content',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');
    }

    public function testThermicianTakeATicketWithPriority(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+0');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_take_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_show_my_ticket');
    }

    private function createPdf(): UploadedFile
    {
        $fileName = 'foo.pdf';
        $filePath = sprintf('%s/foo.pdf', __DIR__);

        return new UploadedFile($filePath, $fileName, null, null, true);
    }

    public function testThermicianSendDocumentTicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+0');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_send_document', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('thermician_send_document');
        $form = $crawler->filter('form[name=document]')->form([
            'document[name]' => 'foo',
            'document[certificate][0]' => $this->createPdf(),
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_send_document');
    }

    public function testThermicianFinishTicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('company+0');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_finish_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_home');
    }

    public function testSecondThermicianTakeATicket(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin2@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+3');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_take_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_show_my_ticket');
    }

    public function testSecondThermicianTryToFinishATicketWithNoDocumentSend(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin2@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+3');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_finish_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_send_document');
    }

    public function testSecondThermicianSendDocument(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin2@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('company+3');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_send_document', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('thermician_send_document');
        $form = $crawler->filter('form[name=document]')->form([
            'document[name]' => 'foo',
            'document[certificate][0]' => $this->createPdf(),
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_send_document');
    }

    public function testSecondThermicianTryToFinishATicketWithDocumentSendCorrect(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin2@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('company+3');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_finish_ticket', [
            'idProject' => $project->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('thermician_home');
    }
}
