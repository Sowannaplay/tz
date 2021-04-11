<?php

namespace Drupal\tz_module\Form;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides ability to change site name
 */
class ArticleManageForm extends FormBase {

  /**
   * Node Storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Article manager constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *   Entity Type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_manager) {
    try {
      $this->nodeManager = $entity_manager->getStorage('node');
    }
    catch (PluginNotFoundException | InvalidPluginDefinitionException $e) {
      watchdog_exception('tz_module_exception', $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'article_administation_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $options = [];
    $articles = $this->nodeManager->loadByProperties(['type' => 'article']);
    foreach($articles as $article) {
      $options += [
        $article->id() => $article->label(),
      ];
    }

    $publish_options = [
      '0' => 'not published',
      '1' => 'published'
    ];

    $form['article_title'] = [
      '#type' => 'select',
      '#title' => $this->t('Article name'),
      '#options' => $options,
    ];

    $form['status'] = [
      '#type' => 'select',
      '#title' => $this->t('Status'),
      '#options' => $publish_options,
      '#default_value' => '1'
    ];

    $form['sticky'] = [
      '#type' => 'select',
      '#title' => $this->t('Sticky'),
      '#options' => [
        '0' => 'sticky',
        '1' => 'not sticky',
      ],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#submit' => [
        [$this, 'submitForm'],
      ],
      '#value' => 'Update',
    ];

    $form['actions']['delete'] = [
      '#type' => 'submit',
      '#submit' => [
        [$this, 'deleteArticle']
      ],
      '#value' => 'Delete',
    ];

    return $form;
  }

  /**
   * Update action.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $article_id = $form_state->getValue('article_title');
    $article_status = $form_state->getValue('status');
    $article = $this->getNodeById($article_id);
    if (!$article instanceof NodeInterface) {
      return;
    }
    $article->set('status', $article_status);
    if ($form_state->getValue('sticky') === '0') {
      $article->setSticky(TRUE);
    }
    else {
      $article->setSticky(FALSE);
    }
    try {
      $article->save();
    }
    catch (EntityStorageException $e) {
      watchdog_exception('tz_module', $e);
    }
  }

  /**
   * Delete action.
   */
  public function deleteArticle(array &$form, FormStateInterface $form_state) {
    $article_id = $form_state->getValue('article_title');
    $article = $this->getNodeById($article_id);
    if (!$article instanceof NodeInterface) {
      return;
    }
    try {
      $article->delete();
    }
    catch (EntityStorageException $e) {
      watchdog_exception('tz_module', $e);
    }
  }

  /**
   * Getting node by id.
   */
  protected function getNodeById($id) {
    $article_array = $this->nodeManager->loadByProperties(['type' => 'article', 'nid' => $id]);
    return reset($article_array);
  }

}
