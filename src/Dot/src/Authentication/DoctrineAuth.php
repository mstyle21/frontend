<?php
/**
 * User: gabidj
 * Date: 2019-02-18
 * Time: 17:19
 */

namespace Dot\Authentication;

use Doctrine\ORM\EntityManager;
use Dot\Authentication\Adapter\AbstractAdapter;
use Dot\Authentication\Adapter\AdapterInterface;
use Dot\Authentication\Adapter\Db\DbCredentials;
use Dot\Authentication\Identity\IdentityInterface;
use Dot\Hydrator\ClassMethodsCamelCase;
use Dot\User\Entity\UserEntity;
use Dot\User\Entity\UserEntityRepository;
use Dot\User\Service\PasswordCheck;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Doctrine\ORM\Tools\Setup;




class DoctrineAuth extends AbstractAdapter implements AdapterInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var DbCredentials
     */
    protected $credentials;

    /**
     * @var array
     */
    protected $identityColumns;

    /**
     * @var string
     */
    protected $credentialColumn;

    /**
     * @var callable
     */
    protected $callbackCheck;

    /**
     * @var string
     */
    protected $identityClass;

    /**
     * @return string
     */
    public function getIdentityClass(): string
    {
        return $this->identityClass;
    }

    /**
     * @param string $identityClass
     */
    public function setIdentityClass(string $identityClass): void
    {
        $this->identityClass = $identityClass;
    }

    /**
     * @return callable
     */
    public function getCallbackCheck():? callable
    {
        return $this->callbackCheck;
    }

    /**
     * @param callable $callbackCheck
     */
    public function setCallbackCheck(callable $callbackCheck): void
    {
        $this->callbackCheck = $callbackCheck;
    }

    /**
     * @return array
     */
    public function getIdentityColumns(): array
    {
        return $this->identityColumns;
    }

    /**
     * @param array $identityColumns
     */
    public function setIdentityColumns(array $identityColumns): void
    {
        $this->identityColumns = $identityColumns;
    }

    /**
     * @return string
     */
    public function getCredentialColumn(): string
    {
        return $this->credentialColumn;
    }

    /**
     * @param string $credentialColumn
     */
    public function setCredentialColumn(string $credentialColumn): void
    {
        $this->credentialColumn = $credentialColumn;
    }


    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return DbCredentials
     */
    public function getCredentials(): ?DbCredentials
    {
        return $this->credentials;
    }

    /**
     * @param DbCredentials $credentials
     */
    public function setCredentials(DbCredentials $credentials = null)
    {
        $this->credentials = $credentials;
    }


    public function __construct(EntityManager $entityManager, array $options = null)
    {
        parent::__construct($options);

        // this ensures the right error is thrown
        $this->identityClass = null;

        if (isset($options['identity_prototype'])) {
            $this->identityClass = get_class($this->identityPrototype);
        }

        if (isset($options['callback_check']) && is_callable($options['callback_check'])) {
            $this->setCallbackCheck($options['callback_check']);
        }

        /** @var UserEntityRepository $repo */
        $this->entityManager = $entityManager;
        $this->identityColumns = $options['identity_columns'];
        if (is_string($this->identityColumns)) {
            // if only a column is provided as string, treat it as a list with one element
            $this->identityColumns = [$this->identityColumns];
        }
        $this->credentialColumn = $options['credential_column'];
    }

    /**
     * @param ServerRequestInterface $request
     * @return void
     */
    public function prepare(ServerRequestInterface $request)
    {
        $this->request = $request;

        /** @var DbCredentials $credentials */
        $credentials = $request->getAttribute(DbCredentials::class, null);
        if ($credentialColumn = $credentials->getIdentityColumn()) {
            $this->setIdentityColumns($credentialColumn);
        }

        if ($credentials && !$credentials instanceof DbCredentials) {
            throw new RuntimeException(
                sprintf(
                    "Adapter needs credentials to be provided as an instance of %s as a request attribute",
                    DbCredentials::class
                )
            );
        }

        $this->setCredentials($credentials);
    }

    /**
     * @return AuthenticationResult
     */
    public function authenticate(): AuthenticationResult
    {
        if (!$this->getCredentials()) {
            return new AuthenticationResult(
                AuthenticationResult::FAILURE_MISSING_CREDENTIALS,
                Utils::$authCodeToMessage[AuthenticationResult::FAILURE_MISSING_CREDENTIALS]
            );
        }

        $credentials = $this->getCredentials();
        $identityColumns = $this->getIdentityColumns();
        $credentialColumn = $this->getCredentialColumn();

        //add identity column to check
        if (!empty($credentials->getIdentityColumn())
            && !in_array($credentials->getIdentityColumn(), $identityColumns)
        ) {
            $identityColumns = array_unshift($identityColumns, $credentials->getIdentityColumn());
        }
        //if passed credentials contain a credential column, overwrite the config one
        if (!empty($credentials->getCredentialColumn())) {
            $credentialColumn = $credentials->getCredentialColumn();
        }

        if (empty($identityColumns) || empty($credentialColumn)) {
            throw new RuntimeException(
                "CallbackCheck adapter requires at least one identity column name and credential column name"
            );
        }

        $password = $credentials->getCredential();
        throw new \Exception('Not yet implemented');
        //go over the identities and stop if one is found
        foreach ($identityColumns as $identityColumn) {
            $repo = $this->entityManager->getRepository($this->identityClass);
            $user = $repo->findOneBy([$identityColumn => $credentials->getIdentity()]);
            $identityArray = $this->getIdentityHydrator()->extract($user);
            $hash = $identityArray[$credentialColumn];

            $callbackCheck = $this->callbackCheck;

            /** @var PasswordCheck */
            $result = $callbackCheck($hash, $password);

            // WORK IN PROGRESS

        }

        // work in progress


        return new AuthenticationResult(
            AuthenticationResult::FAILURE_UNCATEGORIZED,
            Utils::$authCodeToMessage[AuthenticationResult::FAILURE_UNCATEGORIZED]
        );
    }

    /**
     * @return ResponseInterface
     */
    public function challenge(): ResponseInterface
    {
        exit(__FILE__ . ':' . __LINE__);
        // TODO: Implement challenge() method.
    }
}