<?php

namespace Box\Entities\File;

class File
{
    /**
     * @MarshallProperty(name="type", type="string")
     */
    public $type;

    /**
     * @MarshallProperty(name="id", type="string")
     */
    public $id;

    /**
     * @MarshallProperty(name="sequence_id", type="string")
     */
    public $sequence_id;

    /**
     * @MarshallProperty(name="etag", type="string")
     */
    public $etag;

    /**
     * @MarshallProperty(name="name", type="string")
     */
    public $name;

    /**
     * @MarshallProperty(name="created_at", type="string")
     */
    public $created_at;

    /**
     * @MarshallProperty(name="description", type="string")
     */
    public $description;

    /**
     * @MarshallProperty(name="size", type="string")
     */
    public $size;

    /**
     * @MarshallProperty(name="path_collection", type="string")
     */
    public $path_collection;

    /**
     * @MarshallProperty(name="created_by", type="string")
     */
    public $created_by;

    /**
     * @MarshallProperty(name="modified_by", type="string")
     */
    public $modified_by;

    /**
     * @MarshallProperty(name="trashed_at", type="string")
     */
    public $trashed_at;

    /**
     * @MarshallProperty(name="purged_at", type="string")
     */
    public $purged_at;

    /**
     * @MarshallProperty(name="content_created_at", type="string")
     */
    public $content_created_at;

    /**
     * @MarshallProperty(name="content_modified_at", type="string")
     */
    public $content_modified_at;

    /**
     * @MarshallProperty(name="owned_by", type="string")
     */
    public $owned_by;

    /**
     * @MarshallProperty(name="shared_link", type="string")
     */
    public $shared_link;

    /**
     * @MarshallProperty(name="folder_upload_email", type="string")
     */
    public $folder_upload_email;

    /**
     * @MarshallProperty(name="parent", type="string")
     */
    public $parent;

    /**
     * @MarshallProperty(name="item_status", type="string")
     */
    public $item_status;

    /**
     * @MarshallProperty(name="item_collection", type="string")
     */
    public $item_collection;

    /**
     * @MarshallProperty(name="sync_state", type="string")
     */
    public $sync_state;

    /**
     * @MarshallProperty(name="has_collaborations", type="string")
     */
    public $has_collaborations;

    /**
     * @MarshallProperty(name="permissions", type="string")
     */
    public $permissions;

    /**
     * @MarshallProperty(name="tags", type="string")
     */
    public $tags;

    /**
     * @MarshallProperty(name="can_non_owners_invite", type="string")
     */
    public $can_non_owners_invite;

    /**
     * @MarshallProperty(name="is_externally_owned", type="string")
     */
    public $is_externally_owned;

    /**
     * @MarshallProperty(name="allowed_shared_link_access_levels", type="string")
     */
    public $allowed_shared_link_access_levels;

    /**
     * @MarshallProperty(name="allowed_invitee_roles", type="string")
     */
    public $allowed_invitee_roles;

    /**
     * @MarshallProperty(name="watermark_info", type="string")
     */
    public $watermark_info;
}
