[![Latest Version on Packagist](https://img.shields.io/packagist/v/hotrush/laravel-signer.svg?style=flat-square)](https://packagist.org/packages/hotrush/laravel-signer)
[![Total Downloads](https://img.shields.io/packagist/dt/hotrush/laravel-signer.svg?style=flat-square)](https://packagist.org/packages/hotrush/laravel-signer)
[![Tests and coverage](https://github.com/hotrush/laravel-signer/actions/workflows/main.yml/badge.svg)](https://github.com/hotrush/laravel-signer/actions/workflows/main.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/1340b31989a7c80fe93b/maintainability)](https://codeclimate.com/github/hotrush/laravel-signer/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/1340b31989a7c80fe93b/test_coverage)](https://codeclimate.com/github/hotrush/laravel-signer/test_coverage)

# Laravel Signer

Package to create and validate signatures for laravel models.

## Installation

```
composer require hotrush/laravel-signer
```

## Usage

1. Add `Signable` contract to your model

    ```php
    use Hotrush\Signer\Contracts\Signable;
    
    class Post extends Model implements Signable
    {
    
    }
    ```

2. Implement contract methods. To simplify this process `CanBeSigned` trait can be used.

    ```php
    use Hotrush\Signer\Contracts\Signable;
    use Hotrush\Signer\Contracts\Traits\CanBeSigned;
        
    class Post extends Model implements Signable
    {
        use CanBeSigned;
        
        /**
         * Return null if never expires.
         *
         * @return Carbon|null
         */
        public function getSignExpiration(): ?Carbon
        {
            return null;
        }
    
        /**
         * Payload used for making signature hash.
         *
         * @return array
         */
        public function getSignPayload(): array
        {
            return [
                $this->getKeyName() => $this->getKey(),
                'field' => $this->field,
            ];
        }
    
        /**
         * Payload put into encoded code. Will be publicly accessible.
         *
         * @return array
         */
        public function getPublicSignPayload(): array
        {
            return [
                $this->getKeyName() => $this->getKey(),
            ];
        }
        
        /**
         * Define where clause for getting signable model instance by signature.
         * Only values from public payload can be used.
         */
        public static function signableClauses(Signature $signature): \Closure
        {
            return function (Builder $query) use ($signature) {
                $query->where('id', '=', $signature->payload['id']);
            };
        }
    }
    ```

3. Use facade to generate signature

   ```php
   use Hotrush\Signer\Facades\Signer;
   
   $signable = Post::find(1);
   $signature = Signer::generate($signable);
   
   echo (string) $signature;
   ```

   Signature can be converted into a string and send a confirmation code for example.

4. To verify code facade can be used as well. But first need to decode signature.

   ```php
   use Hotrush\Signer\Facades\Signer;
   use Hotrush\Signer\Signature;
   
   // decode signature
   $signature = Signature::decode('signature-string-value');
   
   // get signable
   $signable = Post::findSignable($signature);
   
   // verify
   $valid = Signer::validate($signable, $signature);
   ```
   
## Testing

```
composer test
```