<script lang="ts">
    import AppLayout from "@/layouts/AppLayout.svelte";
    import { type BreadcrumbItem } from '@/types';
    import * as Table from "@/components/ui/table/index.js";
    import * as Dialog from "@/components/ui/dialog/index.js";
    import * as Select from "@/components/ui/select/index.js";
    import { Badge } from "@/components/ui/badge";
    import { Button } from "@/components/ui/button";
    import { router } from "@inertiajs/svelte";
    import Label from "@/components/ui/label/label.svelte";
    import Input from "@/components/ui/input/input.svelte";
    import ConfirmPassword from "../auth/ConfirmPassword.svelte";

    let { users } = $props();

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Admin Dashboard',
            href: '/admin/dashboard',
        },

        {
            title: 'User Management',
            href: '/admin/users',
        },
    ];

    let displayUsers = $state([...users]);
    let sortKey = $state('created_at');
    let isAsc = $state(false);

    function sortBy(column: string) {
        if (sortKey === column) {
            isAsc = !isAsc;
        } else {
            sortKey = column;
            isAsc = true;
        }

        displayUsers = [...displayUsers].sort((a, b) => {
            let valA = a[column];
            let valB = b[column];

            if (column === 'created_at') {
                valA = new Date(valA).getTime();
                valB = new Date(valB).getTime();
            }

            if (valA < valB) return isAsc ? -1 : 1;
            if (valA > valB) return isAsc ? 1 : -1;
            return 0;
        });
    }

    let isDeleting = $state(false);
    let deletedUserID = $state(null);

    function openDelete(user: any) {
        deletedUserID = user.id;
        isDeleting = true;
    }

    function removeUser() {
        router.delete(`/admin/users/${deletedUserID}`, {
            onSuccess: () => {
                displayUsers = displayUsers.filter(u => u.id !== deletedUserID);
                deletedUserID = null;
            }
        });
        isDeleting = false;
    }


    let isEditing = $state(false);
    let editForm = $state({ id: null, name: '', email: '', role: '' });

    function openEdit(user: any) {
        editForm = { ...user };
        isEditing = true;
    }

    function submitEdit() {
        router.put(`/admin/users/${editForm.id}`, editForm, {
            onSuccess: () => {
                isEditing = false;
                const index = displayUsers.findIndex(u => u.id === editForm.id);
                displayUsers[index] = { ...editForm };
            }
        });
    }


    let isCreating = $state(false);
    let createForm = $state({
        id: null,
        name: '',
        email: '',
        password: '',
        role: 'user'
    });

    function submitCreate() {
        router.post('/admin/users', createForm, {
            onSuccess: () => {
                isCreating = false;
                displayUsers.push(createForm);
                createForm = { id: null, name: '', email: '', password: '', role: 'user' };
            }
        });
    }
</script>
<svelte:head>
    <title>PG - Management Users</title>
</svelte:head>

<AppLayout {breadcrumbs}>
  <div class="p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">User Management</h1>
      <Button onclick={() => isCreating = true}>+ Add New User</Button>
    </div>
    <div class="mb-4">
      This is very danger. Only Admin are allows.
        </div>

    <div class="border rounded-md">
      <Table.Root>
        <Table.Header>
          <Table.Row>
            <Table.Cell onclick={() => sortBy('id')} class="cursor-pointer hover:bg-muted font-bold">
                ID {sortKey === 'id' ? (isAsc ? '↑' : '↓') : ''}
            </Table.Cell>
            <Table.Cell onclick={() => sortBy('name')} class="cursor-pointer hover:bg-muted font-bold">
                Name {sortKey === 'name' ? (isAsc ? '↑' : '↓') : ''}
            </Table.Cell>
            <Table.Cell onclick={() => sortBy('email')} class="cursor-pointer hover:bg-muted font-bold">
                Email {sortKey === 'email' ? (isAsc ? '↑' : '↓') : ''}
            </Table.Cell>
            <Table.Cell onclick={() => sortBy('role')} class="cursor-pointer hover:bg-muted font-bold">
                Role {sortKey === 'role' ? (isAsc ? '↑' : '↓') : ''}
            </Table.Cell>
            <Table.Cell onclick={() => sortBy('created_at')} class="cursor-pointer hover:bg-muted font-bold">
                Joined {sortKey === 'created_at' ? (isAsc ? '↑' : '↓') : ''}
            </Table.Cell>
            <Table.Cell class="font-bold">Actions</Table.Cell>
          </Table.Row>
        </Table.Header>
        <Table.Body>
          {#each displayUsers as user}
            <Table.Row>
              <Table.Cell>{user.id}</Table.Cell>
              <Table.Cell class="font-medium">{user.name}</Table.Cell>
              <Table.Cell>{user.email}</Table.Cell>
              <Table.Cell>
                <Badge variant={user.role === 'admin' ? 'default' : 'secondary'}>
                  {user.role}
                </Badge>
              </Table.Cell>
              <Table.Cell>{new Date(user.created_at).toLocaleDateString()}</Table.Cell>
              <Table.Cell>
                <Button onclick={() => openEdit(user)} variant="outline" size="sm">Edit</Button>
                <Button onclick={() => openDelete(user)} variant="destructive" size="sm">Remove</Button>
              </Table.Cell>
            </Table.Row>
          {/each}
        </Table.Body>
      </Table.Root>
    </div>
  </div>

  <Dialog.Root bind:open={isEditing}>
    <Dialog.Content>
      <Dialog.Header>
        <Dialog.Title>Edit User Profile</Dialog.Title>
      </Dialog.Header>

      <div class="grid gap-4 py-4">
        <div class="grid gap-2">
          <Label for="name">Name</Label>
          <Input id="name" bind:value={editForm.name} />
        </div>
        <div class="grid gap-2">
          <Label for="email">Email</Label>
          <Input id="email" type="email" bind:value={editForm.email} />
        </div>
        <div class="grid gap-2">
          <Label for="role">Role</Label>
            <Select.Root type="single" name="newRole" bind:value={editForm.role}>
              <Select.Trigger class="w-full">
                {editForm.role}
              </Select.Trigger>
              <Select.Content>
                <Select.Item
                  value={'user'}
                  label={'User'}
                />
                <Select.Item
                  value={'admin'}
                  label={'Admin'}
                />
              </Select.Content>
            </Select.Root>
        </div>
      </div>
      <Dialog.Footer>
        <Button variant="outline" onclick={() => isEditing = false}>Cancel</Button>
        <Button onclick={submitEdit}>Save Changes</Button>
      </Dialog.Footer>
    </Dialog.Content>
  </Dialog.Root>

  <Dialog.Root bind:open={isDeleting}>
    <Dialog.Content>
      <Dialog.Header>
        <Dialog.Title>Delete This User</Dialog.Title>
      </Dialog.Header>
      <div>Are Your Sure that you want to delete the user?</div>
      <Dialog.Footer>
        <Button variant="outline" onclick={() => isDeleting = false}>Cancel</Button>
        <Button onclick={removeUser}>Yes</Button>
      </Dialog.Footer>
    </Dialog.Content>
  </Dialog.Root>

  <Dialog.Root bind:open={isCreating}>
    <Dialog.Content>
      <Dialog.Header>
        <Dialog.Title>Create New User</Dialog.Title>
      </Dialog.Header>
      <div class="grid gap-4 py-4">
        <div class="grid gap-2">
          <Label for="c-pass">Name</Label>
          <Input id="c-name" placeholder="Full name" required bind:value={createForm.name} />
        </div>
        <div class="grid gap-2">
          <Label for="c-pass">Email address</Label>
          <Input id="c-email" type="email" placeholder="email@example.com" required bind:value={createForm.email} />
        </div>
        <div class="grid gap-2">
          <Label for="c-pass">Password</Label>
          <Input id="c-pass" type="password" placeholder="Password" min='8' required bind:value={createForm.password} />
        </div>
        <div class="grid gap-2">
          <Label for="c-role">Role</Label>
            <Select.Root type="single" name="newRole" required bind:value={createForm.role}>
              <Select.Trigger class="w-full">
                {createForm.role}
              </Select.Trigger>
              <Select.Content>
                <Select.Item
                  value={'user'}
                  label={'User'}
                />
                <Select.Item
                  value={'admin'}
                  label={'Admin'}
                />
              </Select.Content>
            </Select.Root>
        </div>
      </div>
      <Dialog.Footer>
          <Button variant="outline" onclick={() => isCreating = false}>Cancel</Button>
          <Button onclick={submitCreate}>Create</Button>
      </Dialog.Footer>
    </Dialog.Content>
  </Dialog.Root>
</AppLayout>
