<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Contact\LocalRelationship\Entity;

use Friendica\Core\Protocol;
use Friendica\Model\Contact;

/**
 * @property-read int    $userId
 * @property-read int    $contactId
 * @property-read int    $uriId
 * @property-read bool   $blocked
 * @property-read bool   $ignored
 * @property-read bool   $collapsed
 * @property-read bool   $hidden
 * @property-read bool   $pending
 * @property-read int    $rel
 * @property-read string $info
 * @property-read bool   $notifyNewPosts
 * @property-read int    $remoteSelf
 * @property-read int    $fetchFurtherInformation
 * @property-read string $ffiKeywordblacklist
 * @property-read string $hubVerify
 * @property-read string $protocol
 * @property-read int    $rating
 * @property-read int    $priority
 */
class LocalRelationship extends \Friendica\BaseEntity
{
	// Fetch Further Information options, not a binary flag
	const FFI_NONE        = 0;
	const FFI_INFORMATION = 1;
	const FFI_KEYWORD     = 3;
	const FFI_BOTH        = 2;

	const MIRROR_DEACTIVATED    = 0;
	const MIRROR_OWN_POST       = 2;
	const MIRROR_NATIVE_RESHARE = 3;

	/** @var int */
	protected $userId;
	/** @var int */
	protected $contactId;
	/** @var bool */
	protected $blocked;
	/** @var bool */
	protected $ignored;
	/** @var bool */
	protected $collapsed;
	/** @var bool */
	protected $hidden;
	/** @var bool */
	protected $pending;
	/** @var int */
	protected $rel;
	/** @var string */
	protected $info;
	/** @var bool */
	protected $notifyNewPosts;
	/** @var int One of MIRROR_* */
	protected $remoteSelf;
	/** @var int One of FFI_* */
	protected $fetchFurtherInformation;
	/** @var string */
	protected $ffiKeywordblacklist;
	/** @var string */
	protected $hubVerify;
	/** @var string */
	protected $protocol;
	/** @var int */
	protected $rating;
	/** @var int */
	protected $priority;

	public function __construct(int $userId, int $contactId, bool $blocked = false, bool $ignored = false, bool $collapsed = false, bool $hidden = false, bool $pending = false, int $rel = Contact::NOTHING, string $info = '', bool $notifyNewPosts = false, int $remoteSelf = self::MIRROR_DEACTIVATED, int $fetchFurtherInformation = self::FFI_NONE, string $ffiKeywordblacklist = '', string $hubVerify = '', string $protocol = Protocol::PHANTOM, ?int $rating = null, ?int $priority = null)
	{
		$this->userId                  = $userId;
		$this->contactId               = $contactId;
		$this->blocked                 = $blocked;
		$this->ignored                 = $ignored;
		$this->collapsed               = $collapsed;
		$this->hidden                  = $hidden;
		$this->pending                 = $pending;
		$this->rel                     = $rel;
		$this->info                    = $info;
		$this->notifyNewPosts          = $notifyNewPosts;
		$this->remoteSelf              = $remoteSelf;
		$this->fetchFurtherInformation = $fetchFurtherInformation;
		$this->ffiKeywordblacklist      = $ffiKeywordblacklist;
		$this->hubVerify               = $hubVerify;
		$this->protocol                = $protocol;
		$this->rating                  = $rating;
		$this->priority                = $priority;
	}

	public function addFollow()
	{
		$this->rel = in_array($this->rel, [Contact::FOLLOWER, Contact::FRIEND]) ? Contact::FRIEND : Contact::SHARING;
	}

	public function removeFollow()
	{
		$this->rel = in_array($this->rel, [Contact::FOLLOWER, Contact::FRIEND]) ? Contact::FOLLOWER : Contact::NOTHING;
	}

	public function addFollower()
	{
		$this->rel = in_array($this->rel, [Contact::SHARING, Contact::FRIEND]) ? Contact::FRIEND : Contact::FOLLOWER;
	}

	public function removeFollower()
	{
		$this->rel = in_array($this->rel, [Contact::SHARING, Contact::FRIEND]) ? Contact::SHARING : Contact::NOTHING;
	}
}
