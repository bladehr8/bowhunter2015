<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

return array(
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),

    'view_manager' => array(
	 'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'ZfcUser/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'zfcuser' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'zfcuser' => 'ZfcUser\Factory\Controller\UserControllerFactory',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ZfcUser\Authentication\Adapter\Db' => 'ZfcUser\Authentication\Adapter\Db',
            'ZfcUser\Authentication\Storage\Db' => 'ZfcUser\Authentication\Storage\Db',
            'ZfcUser\Form\Login'                => 'ZfcUser\Form\Login',
            'zfcuser_user_service'              => 'ZfcUser\Service\User',
        ),
        'factories' => array(
            'zfcuser_module_options'                        => 'ZfcUser\Factory\ModuleOptionsFactory',
            'zfcuser_auth_service'                          => 'ZfcUser\Factory\AuthenticationServiceFactory',
            'ZfcUser\Authentication\Adapter\AdapterChain'   => 'ZfcUser\Authentication\Adapter\AdapterChainServiceFactory',
            'zfcuser_login_form'                            => 'ZfcUser\Factory\Form\LoginFormFactory',
            'zfcuser_register_form'                         => 'ZfcUser\Factory\Form\RegisterFormFactory',
            'zfcuser_change_password_form'                  => 'ZfcUser\Factory\Form\ChangePasswordFormFactory',
            'zfcuser_change_email_form'                     => 'ZfcUser\Factory\Form\ChangeEmailFormFactory',
            'zfcuser_user_mapper'                           => 'ZfcUser\Factory\UserMapperFactory',
            'zfcuser_user_hydrator'                         => 'ZfcUser\Factory\Mapper\UserHydratorFactory',
        ),
        'aliases' => array(
            'zfcuser_register_form_hydrator' => 'zfcuser_user_hydrator',
            'zfcuser_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'zfcUserAuthentication' => 'ZfcUser\Factory\Controller\Plugin\ZfcUserAuthenticationFactory',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'zfcUserDisplayName'    => 'ZfcUser\Factory\View\Helper\DisplayNameFactory',
            'zfcUserIdentity'       => 'ZfcUser\Factory\View\Helper\IdentityFactory',
            'zfcUserLoginWidget'    => 'ZfcUser\Factory\View\Helper\LoginWidgetFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'authenticate',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
