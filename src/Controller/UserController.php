<?php

namespace NetInteractive\Bundle\UserImporterBundle\Controller;

use NetInteractive\Bundle\UserImporterBundle\Csv\Reader;
use NetInteractive\Bundle\UserImporterBundle\Form\UploaderType;
use NetInteractive\Bundle\UserImporterBundle\Form\UserImportMapperType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(UploaderType::class);

        if ($request->getMethod() === Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /* @var UploadedFile $csvFile */
                $csvTmpFile = tempnam(sys_get_temp_dir(), 'csv');

                $csvFile = $form->getData()['csvFile'];
                $csvFile->move(sys_get_temp_dir(), basename($csvTmpFile));

                $session = $this->get('session');
                $session->set('csv.import.file', $csvTmpFile);

                return $this->redirectToRoute('net_interactive_user_importer_matching');
            }
        }

        return $this->render(
            'NetInteractiveUserImporterBundle:User:upload.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function matchingAction(Request $request)
    {
        /* @var Session $session */
        $session = $this->get('session');
        $fileName = $session->get('csv.import.file');

        if (!$fileName || !file_exists($fileName)) {
            return $this->redirectToRoute('net_interactive_user_importer_upload');
        }

        $reader = Reader::load(
            new File($fileName)
        );

        $form = $this->createForm(UserImportMapperType::class, null, ['csv_fields' => $reader->getHeaders()]);

        if ($request->getMethod() === Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $matcher = $form->getData();
                $importer = $this->get('net_interactive.user.importer');

                $statistics = $importer->import($reader, $matcher);

                $session->remove('csv.import.file');
                $session->set('csv.import.statistics', $statistics);
                unlink($fileName);

                return $this->redirectToRoute('net_interactive_user_importer_statistic');
            }
        }

        return $this->render(
            'NetInteractiveUserImporterBundle:User:importer.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function statisticsAction()
    {
        /* @var Session $session */
        $session = $this->get('session');
        $statistics = $session->get('csv.import.statistics');

        if (!$statistics) {
            return $this->redirectToRoute('net_interactive_user_importer_upload');
        }

        return $this->render(
            'NetInteractiveUserImporterBundle:User:statistics.html.twig',
            ['statistics' => $statistics]
        );
    }
}
