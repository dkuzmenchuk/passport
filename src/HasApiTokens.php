<?php

namespace dkuzmenchuk\PassportRethinkdb;

use Illuminate\Container\Container;

trait HasApiTokens
{
    /**
     * The current access token for the authentication user.
     *
     * @var \dkuzmenchuk\PassportRethinkdb\Token
     */
    protected $accessToken;

    /**
     * Get all of the user's registered OAuth clients.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'user_id');
    }

    /**
     * Get all of the access tokens for the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tokens()
    {
        return $this->hasMany(Token::class, 'user_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the current access token being used by the user.
     *
     * @return \dkuzmenchuk\PassportRethinkdb\Token|null
     */
    public function token()
    {
        return $this->accessToken;
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param  string  $scope
     * @return bool
     */
    public function tokenCan($scope)
    {
        return $this->accessToken ? $this->accessToken->can($scope) : false;
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $scopes
     * @return \dkuzmenchuk\PassportRethinkdb\PersonalAccessTokenResult
     */
    public function createToken($name, array $scopes = [])
    {
        return Container::getInstance()->make(PersonalAccessTokenFactory::class)->make(
            $this->getKey(), $name, $scopes
        );
    }

    /**
     * Set the current access token for the user.
     *
     * @param  \dkuzmenchuk\PassportRethinkdb\Token  $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
