<?php


namespace Jahudka\FakturoidSDK\Entity;

use Jahudka\FakturoidSDK\AbstractEntity;


/**
 * @property int $subjectId
 * @property array $tags
 * @property int $bankAccountId
 * @property string $bankAccount
 * @property string $iban
 * @property string $swiftBic
 * @property string $paymentMethod
 * @property string $currency
 * @property string $exchangeRate
 * @property bool $transferredTaxLiability
 * @property string $vatPriceMode
 * @property-read string $subtotal
 * @property-read string $nativeSubtotal
 * @property-read string $total
 * @property-read string $nativeTotal
 * @property-read string $url
 * @property-read string $htmlUrl
 * @property-read string $subjectUrl
 * @property-read \DateTime $updatedAt
 * @property \ArrayObject|Line[] $lines
 *
 * @method int getSubjectId()
 * @method array getTags()
 * @method int getBankAccountId()
 * @method string getBankAccount()
 * @method string getIban()
 * @method string getSwiftBic()
 * @method string getPaymentMethod()
 * @method string getCurrency()
 * @method string getExchangeRate()
 * @method bool isTransferredTaxLiability()
 * @method string getVatPriceMode()
 * @method string getSubtotal()
 * @method string getNativeSubtotal()
 * @method string getTotal()
 * @method string getNativeTotal()
 * @method string getUrl()
 * @method string getHtmlUrl()
 * @method string getSubjectUrl()
 *
 * @method bool hasSubjectId()
 * @method bool hasTags()
 * @method bool hasBankAccountId()
 * @method bool hasBankAccount()
 * @method bool hasIban()
 * @method bool hasSwiftBic()
 * @method bool hasPaymentMethod()
 * @method bool hasCurrency()
 * @method bool hasExchangeRate()
 * @method bool hasTransferredTaxLiability()
 * @method bool hasVatPriceMode()
 * @method bool hasSubtotal()
 * @method bool hasNativeSubtotal()
 * @method bool hasTotal()
 * @method bool hasNativeTotal()
 * @method bool hasUrl()
 * @method bool hasHtmlUrl()
 * @method bool hasSubjectUrl()
 *
 * @method $this setSubjectId(int $subjectId)
 * @method $this setTags(array $tags)
 * @method $this setBankAccountId(int $bankAccountId)
 * @method $this setBankAccount(string $bankAccount)
 * @method $this setIban(string $iban)
 * @method $this setSwiftBic(string $swiftBic)
 * @method $this setPaymentMethod(string $paymentMethod)
 * @method $this setCurrency(string $currency)
 * @method $this setExchangeRate(string $exchangeRate)
 * @method $this setTransferredTaxLiability(bool $transferredTaxLiability)
 * @method $this setVatPriceMode(string $vatPriceMode)
 */
abstract class AbstractBillable extends AbstractEntity {

    /**
     * @return array
     */
    public function getKnownProperties() {
        return [
            'id',
            'subjectId',
            'tags',
            'bankAccountId',
            'bankAccount',
            'iban',
            'swiftBic',
            'paymentMethod',
            'currency',
            'exchangeRate',
            'transferredTaxLiability',
            'vatPriceMode',
            'subtotal',
            'nativeSubtotal',
            'total',
            'nativeTotal',
            'url',
            'htmlUrl',
            'subjectUrl',
            'updatedAt',
            'lines',
        ];
    }

    /**
     * @return array
     */
    public function getReadonlyProperties() {
        return [
            'subtotal',
            'nativeSubtotal',
            'total',
            'nativeTotal',
            'url',
            'htmlUrl',
            'subjectUrl',
            'updatedAt',
        ];
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function addTag($tag) {
        if (!isset($this->data['tags']) || !in_array($tag, $this->data['tags'], true)) {
            $this->data['tags'][] = $tag;
        }

        return $this;
    }

    /**
     * @param string $tag
     * @return bool
     */
    public function hasTag($tag) {
        return isset($this->data['tags']) && in_array($tag, $this->data['tags'], true);
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function removeTag($tag) {
        if (isset($this->data['tags']) && ($i = array_search($tag, $this->data['tags'], true)) !== false) {
            array_splice($this->data['tags'], $i, 1);
        }

        return $this;
    }

    /**
     * @return \ArrayObject|Line[]
     */
    public function getLines() {
        if (!isset($this->data['lines'])) {
            $this->data['lines'] = new \ArrayObject();
        }

        return $this->data['lines'];
    }

    /**
     * @param \Traversable|array $lines
     * @return $this
     */
    public function setLines($lines) {
        if ($lines instanceof \Traversable) {
            $lines = iterator_to_array($lines);
        }

        if (!is_array($lines)) {
            throw new \InvalidArgumentException("First argument to " . __METHOD__ . " must be either an array or a Traversable object");
        }

        foreach ($lines as $k => $line) {
            if (!($line instanceof Line)) {
                throw new \InvalidArgumentException("Invalid item at key '$k', must be an instance of Line");
            }
        }

        $this->getLines()->exchangeArray($lines);

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt() {
        return isset($this->data['updatedAt']) ? new \DateTime($this->data['updatedAt']) : null;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data) {
        if (isset($data['lines'])) {
            $lines = array_map(function($data) {
                return new Line($data);
            }, $data['lines']);

            $data['lines'] = new \ArrayObject($lines);
        }

        return parent::setData($data);
    }

    /**
     * @return array
     */
    public function toArray() {
        $data = parent::toArray();

        if (isset($data['lines'])) {
            $data['lines'] = array_map(function(Line $line) {
                return $line->toArray();
            }, $data['lines']->getArrayCopy());
        }

        return $data;
    }

    public function getModifiedData() {
        $data = parent::getModifiedData();

        if (isset($data['lines'])) {
            $data['lines'] = array_map(function(Line $line) {
                return $line->toArray();
            }, $data['lines']->getArrayCopy());
        } else {
            foreach ($this->getLines() as $line) {
                if ($modified = $line->getModifiedData()) {
                    $data['lines'][] = $modified;
                }
            }
        }

        return $data;
    }
}
