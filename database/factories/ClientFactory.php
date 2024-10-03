<?php

namespace Laravel\Passport\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Laravel\Passport\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Get the name of the model that is generated by the factory.
     *
     * @return class-string<\Laravel\Passport\Client>
     */
    public function modelName(): string
    {
        return $this->model ?? Passport::clientModel();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => $this->faker->company(),
            'secret' => Str::random(40),
            'redirect_uris' => [$this->faker->url()],
            'grant_types' => ['authorization_code', 'refresh_token'],
            'revoked' => false,
        ];
    }

    /**
     * Use as a public client.
     */
    public function asPublic(): static
    {
        return $this->state([
            'secret' => null,
        ]);
    }

    /**
     * Use as a Password client.
     */
    public function asPasswordClient(): static
    {
        return $this->state([
            'grant_types' => ['password', 'refresh_token'],
            'redirect_uris' => [],
        ]);
    }

    /**
     * Use as a Personal Access Token client.
     */
    public function asPersonalAccessTokenClient(): static
    {
        return $this->state([
            'grant_types' => ['personal_access'],
            'redirect_uris' => [],
        ]);
    }

    /**
     * Use as an Implicit client.
     */
    public function asImplicitClient(): static
    {
        return $this->state([
            'grant_types' => ['implicit'],
            'secret' => null,
        ]);
    }

    /**
     * Use as a Client Credentials client.
     */
    public function asClientCredentials(): static
    {
        return $this->state([
            'grant_types' => ['client_credentials'],
            'redirect_uris' => [],
        ]);
    }
}
