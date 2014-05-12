<?php
/**
 * Created by Alessio Barnini
 * Twitter: @barno7
 * Email : alessio@ibuildings.it
 * Linkedin : https://www.linkedin.com/in/alessiobarnini
 * Web : http://www.ibuildings.it
 *
 * Abbiamo aggiunto dei validatori ad alcuni campi
 * Potete trovare molti validatori a questa pagina
 * http://symfony.com/it/doc/current/book/validation.html
 */

namespace TS\TalkBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\True;

class UtentiType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm ( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('name', 'text', array (
				'constraints' => array (
					new Length( array ('min' => 3, 'max' => '10', 'minMessage' => 'Troppo Corto', 'maxMessage' => 'Troppo lungo') ),
					new Regex( array ('pattern' => "/^[a-zA-Z]+$/", 'message' => 'Sono ammessi solo lettere!') )
				),
			))
			->add('cognome', 'text', array (
				'required' => FALSE
			))
			->add('email', 'text', array (
				'constraints' => array (
					new Email(array('message' => 'Inserisci una email valida'))
				)
			))

			->add('sesso', 'choice', array (
				'choices' => array ('m' => 'Maschio', 'f' => 'Femmina'),
				'expanded' => TRUE,
				'multiple' => FALSE
			))
			->add('save', 'submit');
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions ( OptionsResolverInterface $resolver ) {
		$resolver->setDefaults(array (
			'data_class' => 'TS\TalkBundle\Entity\Utenti'
		));
	}

	/**
	 * @return string
	 */
	public function getName () {
		return 'ts_talkbundle_utenti';
	}
}
