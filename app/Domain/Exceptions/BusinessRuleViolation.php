<?php
namespace App\Domain\Exceptions;
class BusinessRuleViolation extends \RuntimeException{}
class InvalidOrderTransition extends BusinessRuleViolation{}
class SettlementAlreadyProcessed extends BusinessRuleViolation{}
class ShipmentNotDelivered extends BusinessRuleViolation{}
class LockedSetting extends BusinessRuleViolation{}
class InsufficientStock extends BusinessRuleViolation{}
