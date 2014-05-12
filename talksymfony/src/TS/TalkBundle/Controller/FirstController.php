<?php
/**
 * Created by Alessio Barnini
 * Twitter: @barno7
 * Email : alessio@ibuildings.it
 * Linkedin : https://www.linkedin.com/in/alessiobarnini
 * Web : http://www.ibuildings.it
 *
 * Controller che gestisce:
 * - Resa del Form per la creazione di un utente
 * - Query per mostrare gli utenti salvati su Database
 * - Query per "pescare" un utente specifico
 * - Eliminazione di un utente
 * - Caricamento di altri utenti da Database in Ajax
 *
 * Controller :
 * - Form for create a user
 * - Query to show users saved on Database
 * - Query to get a specific user
 * - Delete a User
 * - Load User via Ajax
 *
 */
namespace TS\TalkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use TS\TalkBundle\Entity\Utenti;
use TS\TalkBundle\Form\UtentiType;

class FirstController extends Controller
{
	/**
	 * Creazione Form e salvataggio utente con redirect
	 * Built Form and save User
	 *
	 * @Route("/", name="welcome")
	 * @Template("TSTalkBundle:First:Welcome.html.twig")
	 * @Method({"GET","POST"})
	 */
	public function WelcomeAction ( Request $request ) {
		$utenti = new Utenti();
		$form = $this->createForm(new UtentiType(), $utenti);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getEm();
			$em->persist($utenti);
			$em->flush();
			return $this->redirect($this->generateUrl('user_success', array ('nome_utente' => $utenti->getName())));
		}
		return array (
			'form' => $form->createView()
		);
	}

	/**
	 * Pagina di Atterraggio salvataggio andato a buon fine
	 * Landing page for user created
	 *
	 * @Route("/utente_creato/{nome_utente}", name="user_success")
	 * @Template()
	 * @Method({"GET"})
	 */
	public function UserSuccessAction ( $nome_utente ) {
		return array (
			'nome_utente' => $nome_utente
		);
	}

	/**
	 * Lettura da Database
	 * Read From Database
	 *
	 * @Route("/utenti", name="get_utenti")
	 * @Template()
	 * @Method({"GET"})
	 */
	public function getUtentiAction () {
		$utenti = $this->getDoctrine()->getRepository('TSTalkBundle:Utenti')->findAll();
		return array (
			'utenti' => $utenti
		);
	}


	/**
	 * Lettura da database
	 * Retrieve the specific user ( Ajax Call )
	 *
	 * @Route("/utenti_ajax", name="utenti_ajax", options={"expose"=true})
	 * @Template("@TSTalk/First/more_users.html.twig")
	 * @Method({"GET"})
	 */
	public function getUtentiAjaxAction () {

		$request = $this->get('request');

		$from = $request->get('totale_utenti'); //utenti già visualizzati
		$to = 3; //quanti utenti visualizzare
		$users = $this->getDoctrine()->getRepository('TSTalkBundle:Utenti')->findBy(array(),array(),$to,$from);

		//se arrivo da una chiamata Ajax
		if ($request->isXmlHttpRequest()) {
			$em = $this->getEm();
			$response = new JsonResponse();
			try {
				$em->beginTransaction();
				$out_json = array (
					'status' => "OK",
					'template' => $this->renderView($this->get('request')->attributes->get('_template'),array('users' => $users)), // il template reso è quello che si legge nelle annotation ed in piu passiamo dei parametri
					'mostra_pulsante' => count($users) === $to ? true : false //semplice controllo per mostrare o meno il pulsante per caricare altri users
				);
			} catch (\Exception $e) {
				$out_json = array (
					'status' => "KO",
					'error' => $e->getMessage(),
				);
				$em->rollback();
			}
			$response->setData($out_json);

			return $response;
		}

		throw new HttpException(500, "Solo Chiamate Ajax (potremmo mettere un Redirect)");
	}


	/**
	 * Lettura Specifica di un utente
	 * Retrieve the specific user
	 *
	 * @Route("/dettaglio_utente/{nome}", name="get_utente")
	 * @Template("TSTalkBundle:First:getUtenti.html.twig")
	 * @Method({"GET"})
	 */
	public function DettaglioUtenteAction ( $nome ) {
		$utente = $this->getDoctrine()->getRepository('TSTalkBundle:Utenti')->findOneBy(array ('name' => $nome));

		//un esempio di controllo
		if (!$utente) {
			throw $this->createNotFoundException('Unable to find utente entity.');
		}

		return array (
			'utente' => $utente
		);
	}


	/**
	 * Metodo "alternativo" per effettuare le query, molto potente
	 *
	 *
	 * L'annotazione @ParamConverter richiama dei convertitori, per convertire parametri della richiesta in oggetti.
	 * Tali oggetti sono memorizzati come attributi della richiesta e quindi possono essere iniettati come parametri dei metodi del controllore
	 * http://symfony.com/it/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
	 *
	 * Another Method for retrieve
	 *
	 * The @ParamConverter annotation calls converters to convert request parameters to objects.
	 * These objects are stored as request attributes and so they can be injected as controller method arguments
	 *
	 * http://symfony.com/en/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
	 *
	 * @Route("/cancella_utente/{name}",name="cancella_utente")
	 * @Template("@TSTalk/First/cancellazione_utente.html.twig")
	 * @ParamConverter("Utenti", class="TSTalkBundle:Utenti", options={"mapping": {"name" : "name"}})
	 * @Method({"GET"})
	 */
	public function CancellaUtenteAction ( Utenti $utente ) {
		$em = $this->getEm();
		$em->remove($utente);
		$em->flush();
		return array ();
	}


	/**
	 *
	 * Helper Function
	 *
	 * @return \Doctrine\ORM\EntityManager
	 */
	private function getEm () {
		return $this->getDoctrine()->getManager();
	}


}