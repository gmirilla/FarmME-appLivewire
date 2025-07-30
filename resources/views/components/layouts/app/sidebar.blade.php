<!DOCTYPE html>
@php

    use Illuminate\Support\Facades\Request;
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
    <head>
        @include('partials.head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @PwaHead
            <script src="https://cdn.jsdelivr.net/npm/dexie@3.2.2/dist/dexie.min.js"></script>
    <script>
  const db = new Dexie("FarmEntranceskano");
 db.version(4).stores({
  // Basic Farm Info
  farms: "farmcode,community,farmname,farmstate,inspectorid",

  //Main Form Submission
  forms: "++id,farmcode,farmname,community,crop,cropvariety,regdate,address,sync_status",

  //Volumes Sold (Section B)
  volumes: "++id,farmcode,season,volume", 

  // Agrochemical Use (Section D)
  agrochemicals: "++id,farmcode,herbicide,quantity,applier,hectare,season",

  // Other Cultivated Crops (Section E)
  otherCrops: "++id,farmcode,plotName,crop,area,location,season",

  // Report Details
  reports: "reportid,reportname,reportstate", 

  // Report Sections
  reportSections: "sectionid,reportid,sectionname,sectionseq,sectionstate",

  // Report Questions
  reportQuestions: "questionid,sectionid,reportid,questionseq,question,questiontype,questionstate",

  // Inspection Sheet Header
  inspectionSheet: "++id,farmcode,reportid,inspectorid,season",

  // Inspection Sheet Answers
  inspectionAnswers: "++id,farmcode,inspectionsheetid,questionid,answer,comment"
});
</script>
<script src="/js/offline-handler.js"></script>

    </head>
    <style>

        .c-sidebar a {
            color:#F5F0E6;
            border-color: #388E3C !important;
            text-decoration:none;

        }
        .c-navitem-selected {
            background: #D72638
;

        }
                .c-sidebar a:hover {
            background: #D72638
;

        }
    </style>
    @php
         Auth::check();
        $user = Auth::user();
    @endphp
    <body class="min-h-screen bg-white dark:bg-zinc-800" style="background: -webkit-gradient(linear, left top, right top, from(#f2eadc), to(#F5F0E6)) 0% 0% no-repeat padding-box">
        <flux:sidebar  stashable  sticky class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900" style="background-color:#388E3C ; color: #f5f0e6;" >
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>
            
           

            <flux:navlist variant="outline" wire:ignore>
                <flux:navlist.group heading="Platform" class="grid c-sidebar">
                     <flux:menu.separator />
                    @if (in_array($user->roles,['ADMINISTRATOR','INSPECTOR']) )
            
                    <flux:navlist.item  class="c-sidebar" icon="chart-bar" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate style="margin-top:8px;">{{ __('Dashboard') }}</flux:navlist.item>                 
                    <flux:navlist.item icon="users" href='/farm' style="margin-top:8px;">Farmers</flux:navlist.item>
                    <flux:navlist.item icon="photo" :href="route('onboarding')" style="margin-top:8px;">Farm Entrance</flux:navlist.item>
                    <flux:navlist.item icon="document-magnifying-glass" href='/inspection' style="margin-top:8px;">Farm Inspections</flux:navlist.item>
                    @if ($user->roles=='ADMINISTRATOR')
                    <flux:navlist.item icon="wrench-screwdriver" href='/report' style="margin-top:8px;">Report Config</flux:navlist.item>
                    <flux:navlist.item icon="user-circle" href='/user_admin' style="margin-top:8px;">User Admin</flux:navlist.item>                   
                    <flux:navlist.item icon="clipboard-document-check" href='/inspection_approval' style="margin-top:8px;">Inspection Reviews</flux:navlist.item>
                    @endif
                    
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        @RegisterServiceWorkerScript
    </body>
</html>
