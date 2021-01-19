<?php

namespace DarkGhostHunter\Larapass\WebAuthn;

use Webauthn\AuthenticatorSelectionCriteria as WebAuthnAuthenticatorSelectionCriteria;

class AuthenticatorSelectionCriteria extends WebAuthnAuthenticatorSelectionCriteria
{
    private $residentKey;

    /**
     * Sets the Resident Key variable.
     *
     * @param string|null $residentKey
     * @return AuthenticatorSelectionCriteria
     */
    public function setResidentKey(?string $residentKey): self
    {
        if (! in_array($residentKey, [self::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            self::USER_VERIFICATION_REQUIREMENT_PREFERRED,
            self::USER_VERIFICATION_REQUIREMENT_DISCOURAGED], false)) {
            throw new \RuntimeException("The {$residentKey} as Resident Key option is unsupported.");
        }

        $this->residentKey = $residentKey;
    }

    public function getResidentKey(): ?string
    {
        return $this->residentKey;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize() : array
    {
        $serialied = parent::jsonSerialize();

        if (null !== $this->residentKey) {
            $serialied['residentKey'] = $this->residentKey;
        }

        return $serialied;
    }
}
